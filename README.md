# AuStats Web (Suara Statistik BPS Sulut)
> **Sistem Informasi Publikasi Berbasis Audio & Aksesibilitas Disabilitas Netra**  
> Badan Pusat Statistik (BPS) Provinsi Sulawesi Utara - IPDS

Aplikasi ini mengonversi dokumen publikasi statistik dan Berita Resmi Statistik (BRS) BPS menjadi audio ringkasan berkualitas tinggi secara otomatis menggunakan kecerdasan buatan (**Gemini AI Text-to-Speech** & **FFmpeg**). Sistem dilengkapi antarmuka web yang ramah aksesibilitas (*screen reader friendly*, *voice navigation*, *hover-to-speech*), serta **REST API v1** siap pakai untuk aplikasi mobile (**Flutter**).

---

## Daftar Isi
1. [Arsitektur Sistem](#1-arsitektur-sistem)
2. [Alur Logika Backend (Core Business Logic)](#2-alur-logika-backend-core-business-logic)
   - [A. Pipeline Konversi Dokumen ke Audio (Background Queue)](#a-pipeline-konversi-dokumen-ke-audio-background-queue)
   - [B. Layanan Ekstraksi Teks (TextExtractionService)](#b-layanan-ekstraksi-teks-textextractionservice)
   - [C. Layanan Sintesis Suara AI (TextToSpeechService)](#c-layanan-sintesis-suara-ai-texttospeechservice)
   - [D. Streaming Audio HTTP 206 Range (AudioController)](#d-streaming-audio-http-206-range-audiocontroller)
   - [E. Integrasi WebAPI Resmi BPS (BPSDocumentController)](#e-integrasi-webapi-resmi-bps-bpsdocumentcontroller)
3. [Implementasi di Frontend Web](#3-implementasi-di-frontend-web)
   - [A. Pemutar Audio Spotify-Style (UnifiedAudioManager)](#a-pemutar-audio-spotify-style-unifiedaudiomanager)
   - [B. Voice Search & Voice Navigation (Web Speech API)](#b-voice-search--voice-navigation-web-speech-api)
   - [C. Universal Hover Text-to-Speech (Aksesibilitas)](#c-universal-hover-text-to-speech-aksesibilitas)
   - [D. Pemantauan Progres Realtime (Progress Polling)](#d-pemantauan-progres-realtime-progress-polling)
4. [Spesifikasi REST API v1 untuk Frontend Mobile (Flutter)](#4-spesifikasi-rest-api-v1-untuk-frontend-mobile-flutter)
5. [Skema Basis Data (Database Schema)](#5-skema-basis-data-database-schema)
6. [Panduan Instalasi & Menjalankan Proyek](#6-panduan-instalasi--menjalankan-proyek)

---

## 1. Arsitektur Sistem

```
┌────────────────────────────────────────────────────────────────────────┐
│                          SUMBER DATA DOKUMEN                           │
│     [Upload Admin: PDF/Word]     atau     [Sinkronisasi WebAPI BPS]     │
└───────────────────┬───────────────────────────────────┬────────────────┘
                    │                                   │
                    ▼                                   ▼
┌────────────────────────────────────────────────────────────────────────┐
│                        LARAVEL BACKEND PIPELINE                        │
│                                                                        │
│ 1. DocumentManagementController: Validasi berkas, hash SHA256 & simpan │
│ 2. ProcessDocumentJob (Queue Worker / Asynchronous):                   │
│    ├── TextExtractionService (smalot/pdfparser & PhpWord)              │
│    ├── Text Cleaning & Chunking (Max 3.800 Karakter)                   │
│    ├── Gemini API Text-to-Speech (Rotasi GeminiApiKey aktif)           │
│    ├── FFmpeg Processing (Penggabungan chunk audio -> MP3 & FLAC)      │
│    └── Metadata & Cache Progress Tracker (0% -> 100%)                  │
│                                                                        │
│ 3. Streaming Engine (AudioController):                                 │
│    └── HTTP 206 Partial Content (Mendukung seeking/scrubbing audio)    │
└───────────────────┬───────────────────────────────────┬────────────────┘
                    │                                   │
                    ▼                                   ▼
┌──────────────────────────────────────┐ ┌───────────────────────────────┐
│          FRONTEND WEB BLADE          │ │    FRONTEND MOBILE (FLUTTER)   │
│ • Spotify-Style Audio Player         │ │ • Dio HTTP Client (/api/v1)   │
│ • Voice Search (SpeechRecognition)   │ │ • just_audio (HTTP Range 206) │
│ • Universal Hover Text-to-Speech     │ │ • Background Notification     │
│ • Sound Effects UI (click, hover)    │ │ • Offline Storage Download    │
└──────────────────────────────────────┘ └───────────────────────────────┘
```

---

## 2. Alur Logika Backend (Core Business Logic)

### A. Pipeline Konversi Dokumen ke Audio (Background Queue)
- **File Terkait:**  
  - Controller: [`app/Http/Controllers/Admin/DocumentManagementController.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/app/Http/Controllers/Admin/DocumentManagementController.php)
  - Queue Job: [`app/Jobs/ProcessDocumentJob.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/app/Jobs/ProcessDocumentJob.php)
- **Logika Bisnis:**
  1. Admin mengunggah berkas publikasi (PDF, DOC, DOCX maksimal 50MB) dan cover opsional.
  2. Berkas divalidasi keamanannya (cek enkripsi/proteksi password PDF).
  3. Dokumen disimpan ke storage disk `documents` dengan struktur path: `files/{year}/{id}_document.{ext}`.
  4. Controller menjalankan `ProcessDocumentJob::dispatch($document)` dan langsung mengembalikan respon redirect ke admin, sehingga admin tidak perlu menunggu browser loading lama.
  5. Antrean background worker (`php artisan queue:listen`) mengambil tugas tersebut dan memperbarui status progres di cache Redis/File (`document_progress_{id}`).

### B. Layanan Ekstraksi Teks (`TextExtractionService`)
- **File Terkait:** [`app/Services/TextExtractionService.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/app/Services/TextExtractionService.php)
- **Logika Bisnis:**
  - **Berkas PDF:** Diekstrak per-halaman menggunakan pustaka `Smalot\PdfParser\Parser`. Teks disaring dari header/footer berulang, nomor halaman, dan karakter anomali.
  - **Berkas Word (DOCX):** Diekstrak menggunakan `PhpOffice\PhpWord\IOFactory` dengan menelusuri elemen paragraf dan tabel secara sekuensial.
  - Teks hasil ekstraksi disimpan ke kolom `extracted_text` pada tabel `documents`.

### C. Layanan Sintesis Suara AI (`TextToSpeechService`)
- **File Terkait:** [`app/Services/TextToSpeechService.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/app/Services/TextToSpeechService.php)
- **Logika Bisnis:**
  1. **Rotasi API Key Otomatis:** Mengambil daftar API Key Gemini yang berstatus `active` dari tabel `api_keys` (`GeminiApiKey::class`). Jika satu key terkena *rate limit* (HTTP 429), sistem otomatis berganti ke key berikutnya.
  2. **Chunking Teks:** Teks panjang dipecah per batas kalimat/paragraf dengan panjang maksimal 3.800 karakter agar tidak melebihi batasan payload model AI.
  3. **Generasi Suara:** Setiap chunk dikirim ke endpoint Google Gemini TTS API (`gemini-2.5-flash-preview-tts:generateContent`) dengan suara bernada natural (default: voice `Kore`).
  4. **Penggabungan dengan FFmpeg:** Berkas audio biner hasil dari seluruh chunk digabungkan (*concat*) dan di-encode ke format MP3 (128 kbps) dan format lossless FLAC.
  5. File audio final disimpan di disk penyimpanan dokumen (`storage/app/documents/{id}/audio.mp3`).

### D. Streaming Audio HTTP 206 Range (`AudioController`)
- **File Terkait:** [`app/Http/Controllers/AudioController.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/app/Http/Controllers/AudioController.php)
- **Logika Bisnis:**
  - Endpoint: `GET /audio/stream/{document}/{format?}` atau `GET /api/v1/audio/stream/{document}/{format?}`.
  - Audio player pada browser maupun mobile (misal: Flutter `just_audio`) membutuhkan dukungan **HTTP Range Requests**.
  - Saat user melakukan *seek/scrubbing* maju-mundur di timeline audio, player mengirimkan header `Range: bytes=START-END`.
  - `AudioController` membaca header tersebut, memotong aliran berkas secara efisien, mengembalikan status **HTTP 206 Partial Content** beserta header `Content-Range`, dan menaikkan nilai `play_count` dokumen secara otomatis.

### E. Integrasi WebAPI Resmi BPS (`BPSDocumentController`)
- **File Terkait:** [`app/Http/Controllers/BPSDocumentController.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/app/Http/Controllers/BPSDocumentController.php)
- **Logika Bisnis:**
  - Menghubungkan sistem ke API Web Resmi BPS (`https://webapi.bps.go.id/v1/api`) menggunakan `BPS_API_KEY` dan kode domain provinsi `7100` (Sulawesi Utara).
  - Mengambil daftar publikasi terbaru, metadata ringkasan, dan tautan unduhan PDF resmi untuk diimpor langsung ke dalam sistem tanpa perlu upload manual satu per satu.

---

## 3. Implementasi di Frontend Web

Frontend dibangun menggunakan **Blade Template Engine + Tailwind CSS + Vanilla JS Modular** dengan arsitektur manager yang terpusat di `resources/js/src/`.

### A. Pemutar Audio Spotify-Style (`UnifiedAudioManager`)
- **Blade View:** [`resources/views/components/audio-player.blade.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/resources/views/components/audio-player.blade.php)
- **JS Controller:** [`resources/js/src/managers/UnifiedAudioManager.js`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/resources/js/src/managers/UnifiedAudioManager.js)
- **Fitur:**
  - Bar pemutar audio mengambang (*fixed bottom player*) yang selalu aktif saat bernavigasi.
  - Tombol Play/Pause (Shortcut: `Spasi`), Mundur 10 detik (`Panah Kiri`), Maju 10 detik (`Panah Kanan`).
  - Progress bar seekable dengan visualisasi waktu berjalan (`current-time`) dan total durasi.
  - Pemilihan format audio (MP3 / FLAC) dan pengatur volume suara.

### B. Voice Search & Voice Navigation (`enhanced-voice-search.js`)
- **File:** [`resources/js/enhanced-voice-search.js`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/resources/js/enhanced-voice-search.js)
- **Cara Kerja:**
  1. Memanfaatkan browser **Web Speech API** (`webkitSpeechRecognition`).
  2. Saat tombol mikrofon ditekan, sistem mendengarkan perintah suara user dalam bahasa Indonesia (`id-ID`).
  3. Suara disaring dengan fungsi `sanitizeQuery` (membersihkan kata pengantar seperti *"tolong carikan"*, *"data bps"*, *"ingin lihat"*).
  4. Perintah suara interaktif yang didukung:
     - *"cari inflasi tahun 2024"* -> Otomatis melakukan filter dan submit query pencarian.
     - *"putar dokumen nomor satu"* -> Memanggil fungsi `selectDocument(1)` untuk langsung memutar audio dokumen urutan teratas.

### C. Universal Hover Text-to-Speech (Aksesibilitas)
- **File:** [`resources/js/src/managers/UniversalHoverTextManager.js`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/resources/js/src/managers/UniversalHoverTextManager.js)
- **Cara Kerja:**
  - Didesain khusus untuk penyandang disabilitas low-vision / tuna netra.
  - Setiap elemen teks (judul, deskripsi, menu navigasi, tombol) diberi event listener `mouseenter`.
  - Saat kursor diarahkan ke elemen teks selama lebih dari 300ms, antarmuka browser akan membacakan teks tersebut secara lantang menggunakan `window.speechSynthesis`.

### D. Pemantauan Progres Realtime (Progress Polling)
- **File:** [`resources/views/components/progress-notifications.blade.php`](file:///c:/PROJECT/MAGANGHUB/BPS%20-%20SULUT/IPDS/PROJECT/austats%E2%80%93web/resources/views/components/progress-notifications.blade.php)
- **Cara Kerja:**
  - Saat admin mengunggah dokumen baru, komponen ini menjalankan interval polling ke `GET /admin/progress/{document}`.
  - Respon JSON berisi status tahapan (`extracting_text`, `tts_starting`, `completed`) dan persentase angka (0 - 100%) ditampilkan dalam bentuk notification toast dengan progress bar dinamis.

---

## 4. Spesifikasi REST API v1 untuk Frontend Mobile (Flutter)

Seluruh rute API publik untuk konsumsi aplikasi mobile terdaftar di `routes/web.php` dalam grup `/api/v1` dengan rate limiter otomatis.

### 1. Mengambil Daftar Dokumen Ber-Audio
- **Endpoint:** `GET /api/v1/documents`
- **Query Parameters:**
  - `page` (integer): Halaman data (default: 1).
  - `per_page` (integer): Jumlah data per halaman (default: 12, max: 50).
  - `type` (string): Filter jenis dokumen (`brs` atau `publication`).
  - `year` (integer): Filter tahun rilis (contoh: `2024`).
  - `search` (string): Kata kunci pencarian judul atau ringkasan.
- **Contoh Response JSON:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Perkembangan Indeks Harga Konsumen Sulawesi Utara Januari 2024",
        "slug": "perkembangan-ihk-sulut-jan-2024",
        "type": "brs",
        "year": 2024,
        "description": "Ringkasan data inflasi dan deflasi bulanan di Sulut...",
        "cover_url": "http://127.0.0.1:8000/documents/1/cover",
        "audio": {
          "mp3_url": "http://127.0.0.1:8000/audio/stream/1/mp3",
          "flac_url": null,
          "duration": 210,
          "duration_formatted": "03:30",
          "mp3_size": 3360000,
          "formats_available": ["mp3"]
        }
      }
    ],
    "last_page": 4,
    "total": 45
  }
}
```

### 2. Streaming Audio Berkas
- **Endpoint:** `GET /api/v1/audio/stream/{id}/mp3`
- **Headers:** Menerima header `Range: bytes=0-` (HTTP 206 Support).
- **Penggunaan di Flutter (just_audio):**
```dart
final player = AudioPlayer();
await player.setUrl("http://10.0.2.2:8000/api/v1/audio/stream/1/mp3");
player.play();
```

### 3. Mengambil Metadata Dokumen Tunggal
- **Endpoint:** `GET /api/v1/documents/{id}/metadata`
- **Fungsi:** Mengambil informasi durasi, ukuran berkas biner, dan status konversi secara mendetail.

### 4. Playlist Audio Batch
- **Endpoint:** `POST /api/v1/documents/playlist`
- **Request Body JSON:** `{"document_ids": [1, 5, 8]}`
- **Fungsi:** Mengambil URL audio untuk beberapa dokumen sekaligus guna membuat antrean pemutaran beruntun di aplikasi mobile.

---

## 5. Skema Basis Data (Database Schema)

Tabel utama yang digunakan sistem meliputi:

| Nama Tabel | Peran Utama | Kolom Kunci |
| :--- | :--- | :--- |
| **`documents`** | Data dokumen statistik & status audio | `id`, `title`, `slug`, `type`, `year`, `indicator_id`, `status` (`pending`, `processing`, `completed`, `failed`), `file_path`, `cover_path`, `extracted_text`, `play_count`, `processing_metadata` |
| **`indicators`** | Kategori/Indikator statistik BPS | `id`, `name` (Inflasi, Kemiskinan, PDRB, dll), `slug`, `is_active` |
| **`api_keys`** | Pool token API Gemini untuk rotasi | `id`, `provider`, `api_key`, `status` (`active`, `rate_limited`, `disabled`), `usage_count`, `last_used_at` |
| **`visitor_logs`** | Catatan analitik pengunjung | `id`, `ip_address`, `user_agent`, `document_id`, `visited_at` |
| **`jobs` & `failed_jobs`** | Manajemen antrean proses background | `id`, `queue`, `payload`, `attempts`, `reserved_at` |

---

## 6. Panduan Instalasi & Menjalankan Proyek

### Prasyarat
- PHP >= 8.2 (disertai ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `gd`/`imagick`)
- Composer >= 2.0
- Node.js >= 18.x & NPM
- MySQL / MariaDB Server

### Langkah Menjalankan
1. **Clone repository dan siapkan `.env`:**
   ```bash
   cp .env.example .env
   ```
2. **Install dependensi PHP & JS:**
   ```bash
   composer install --ignore-platform-req=php
   npm install
   ```
3. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```
4. **Migrasi Database & Buat Storage Link:**
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```
5. **Jalankan Aplikasi:**
   ```bash
   composer run dev
   ```
   *(Menjalankan web server di `http://127.0.0.1:8000`, worker antrean audio, dan compiler Vite secara bersamaan)*.

### Akun Administrator Default:
- **URL Login:** `http://127.0.0.1:8000/admin/login`
- **Email:** `admin@audiostatistik.com`
- **Password:** `password`
