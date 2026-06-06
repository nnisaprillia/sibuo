# Refactor Design — Sistem Ujian Online Laravel 13
> Dokumen ini adalah panduan desain komprehensif untuk merombak tampilan aplikasi ujian online. Ikuti setiap aturan dan spesifikasi di bawah ini secara konsisten di seluruh halaman.
 
---
 
## 1. Design System (Wajib Diterapkan Global)
 
### 1.1 Palet Warna
 
```css
/* Warna Utama */
--color-primary:        #0f2744;   /* Navy gelap — sidebar, header, tombol utama */
--color-primary-light:  #3884FF;   /* Biru cerah — aksen, link, icon aktif */
--color-primary-muted:  #60A5FA;   /* Biru muda — teks di atas dark bg, timer */
 
/* Warna Status */
--color-success-bg:   #F0FDF4;   --color-success-text:  #166534;
--color-warning-bg:   #FFFBEB;   --color-warning-text:  #92400E;
--color-danger-bg:    #FEF2F2;   --color-danger-text:   #991B1B;
--color-info-bg:      #EFF6FF;   --color-info-text:     #1D4ED8;
 
/* Warna Netral (gunakan Tailwind default, ini sebagai referensi) */
--color-surface:      white;
--color-page-bg:      #F9FAFB;   /* gray-50 */
--color-border:       #E5E7EB;   /* gray-200 */
--color-text-main:    #111827;   /* gray-900 */
--color-text-muted:   #6B7280;   /* gray-500 */
--color-text-faint:   #9CA3AF;   /* gray-400 */
```
 
### 1.2 Tipografi (Tailwind Classes)
 
| Elemen | Class Tailwind |
|---|---|
| Page title / H1 | `text-xl font-medium text-gray-900` |
| Section heading / H2 | `text-base font-medium text-gray-900` |
| Card label / H3 | `text-sm font-medium text-gray-900` |
| Body text | `text-sm text-gray-700` |
| Secondary / meta | `text-xs text-gray-500` |
| Data angka besar | `text-2xl font-medium text-gray-900` |
| Badge / chip | `text-xs font-medium` |
 
**Font stack:** gunakan `font-sans` (default Tailwind, menggunakan Inter atau system font). Tidak perlu custom font tambahan.
 
### 1.3 Spacing & Layout
 
- Semua halaman authenticated menggunakan layout **sidebar kiri + konten kanan**
- Sidebar: `w-44` (176px) atau `w-48` (192px), fixed
- Content area: `flex-1`, padding `p-5` atau `p-6`
- Topbar (header dalam area konten): `h-12`, border-bottom
- Gap antar card: `gap-3` atau `gap-4`
- Padding dalam card: `p-4` atau `p-5`
### 1.4 Komponen Dasar
 
#### Sidebar
```html
<aside class="w-44 bg-[#0f2744] flex flex-col h-screen sticky top-0">
  <!-- Brand -->
  <div class="flex items-center gap-2 px-4 py-5 border-b border-white/10">
    <div class="w-7 h-7 bg-blue-500 rounded-lg flex items-center justify-center">
      <!-- Icon -->
    </div>
    <span class="text-white text-sm font-medium">ExamHub</span>
  </div>
 
  <!-- Nav items -->
  <nav class="flex flex-col flex-1 py-3">
    <!-- Active item -->
    <a href="#" class="flex items-center gap-2 px-4 py-2 text-xs text-blue-300 bg-blue-500/20 border-r-2 border-blue-400">
      <!-- Icon --> Dashboard
    </a>
    <!-- Inactive item -->
    <a href="#" class="flex items-center gap-2 px-4 py-2 text-xs text-white/50 hover:text-white/80 hover:bg-white/5 transition-colors">
      <!-- Icon --> Menu Lain
    </a>
    <!-- Section label -->
    <p class="px-4 pt-4 pb-1 text-[10px] text-white/30 uppercase tracking-widest">Akademik</p>
  </nav>
 
  <!-- Bottom items (Logout) -->
  <div class="border-t border-white/10 py-2">
    <a href="#" class="flex items-center gap-2 px-4 py-2 text-xs text-white/50 hover:text-white/80 transition-colors">
      <!-- Icon --> Keluar
    </a>
  </div>
</aside>
```
 
#### Topbar / Page Header
```html
<div class="h-12 bg-white border-b border-gray-200 flex items-center justify-between px-5">
  <h1 class="text-sm font-medium text-gray-900">Judul Halaman</h1>
  <div class="flex items-center gap-3">
    <!-- Avatar, bell, dsb -->
  </div>
</div>
```
 
#### Card Container
```html
<div class="bg-white border border-gray-200 rounded-xl p-4">
  <div class="flex items-center justify-between mb-3">
    <h3 class="text-xs font-medium text-gray-900">Judul Card</h3>
    <a href="#" class="text-[10px] text-blue-500">Lihat semua →</a>
  </div>
  <!-- Konten -->
</div>
```
 
#### Stat Card (untuk angka ringkasan)
```html
<div class="bg-white border border-gray-200 rounded-xl p-4">
  <p class="text-xs text-gray-500 mb-1">Label Statistik</p>
  <p class="text-2xl font-medium text-gray-900">24</p>
  <span class="inline-block mt-1 text-[10px] font-medium px-2 py-0.5 rounded bg-blue-50 text-blue-700">
    deskripsi singkat
  </span>
</div>
```
 
#### Badge / Status Chip
```html
<!-- Gunakan sesuai status -->
<span class="text-[10px] font-medium px-2 py-0.5 rounded bg-green-50 text-green-800">Aktif</span>
<span class="text-[10px] font-medium px-2 py-0.5 rounded bg-yellow-50 text-yellow-800">Terjadwal</span>
<span class="text-[10px] font-medium px-2 py-0.5 rounded bg-blue-50 text-blue-700">Draft</span>
<span class="text-[10px] font-medium px-2 py-0.5 rounded bg-red-50 text-red-800">Nonaktif</span>
```
 
#### Tombol
```html
<!-- Tombol Utama -->
<button class="px-4 py-2 bg-[#0f2744] text-white text-xs font-medium rounded-lg hover:bg-[#1a3a5c] transition-colors">
  Simpan
</button>
 
<!-- Tombol Sekunder -->
<button class="px-4 py-2 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
  Batal
</button>
 
<!-- Tombol Bahaya -->
<button class="px-4 py-2 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 border border-red-200 transition-colors">
  Hapus
</button>
```
 
#### Form Input
```html
<div class="space-y-1">
  <label class="block text-xs font-medium text-gray-500">Label Field</label>
  <input type="text"
    class="w-full h-9 px-3 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-400 focus:outline-none transition-colors"
    placeholder="Placeholder...">
</div>
```
 
#### Table (Data CRUD)
```html
<table class="w-full text-xs">
  <thead>
    <tr class="border-b border-gray-200">
      <th class="text-left py-2 text-gray-500 font-medium">Kolom</th>
    </tr>
  </thead>
  <tbody>
    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
      <td class="py-2.5 text-gray-800">Data</td>
    </tr>
  </tbody>
</table>
```
 
---
 
## 2. Halaman Login
 
**Route:** `/` → `auth.login`
 
### Layout
- **Full screen split 50/50**: kiri panel biru gelap (#0f2744), kanan form putih
- Tidak ada sidebar, tidak ada topbar
- Tinggi: `min-h-screen`
### Panel Kiri (Branding)
- Background: `#0f2744`
- Susun secara vertikal dengan `justify-between`: brand logo di atas, hero text di tengah, statistik kecil di bawah
- Brand: icon sekolah + nama aplikasi (putih)
- Hero title: `text-2xl font-medium text-white`, keyword utama berwarna `#60A5FA`
- Sub-teks: `text-xs text-white/50`
- Statistik: 3 kolom (Total Guru, Total Siswa, Mata Pelajaran) — angka `#60A5FA`, label `text-white/40 text-[10px]`
- Boleh tambahkan dekorasi berupa 2 lingkaran transparan besar di pojok sebagai latar tekstur
### Panel Kanan (Form)
- Background: `white`
- Judul: `"Masuk ke akun"` — `text-xl font-medium`
- Sub: `"Masukkan kredensial sesuai peran Anda"` — `text-xs text-gray-500`
- Field: Email, Password (dengan link "Lupa password?" di kanan label)
- Tombol Login: full-width, background `#0f2744`
- Divider: `atau masuk sebagai`
- 3 role pill (Admin / Guru / Siswa): masing-masing sepertiga lebar, style toggle, pill aktif berwarna biru
### Catatan Penting
- Pill role **tidak** mengubah endpoint login — hanya UI visual. Role dideteksi backend setelah login.
- Setelah login sukses, redirect otomatis sesuai role (sudah ada di controller)
- Tampilkan pesan error Breeze di atas form jika ada (class `text-xs text-red-600`)
---
 
## 3. Dashboard Admin
 
**Route:** `/admin/dashboard` → `admin.dashboard`
 
### Layout
- Sidebar kiri (navy) + main content area
- Topbar dengan judul "Dashboard Admin" + avatar
### Isi Konten
 
**Baris 1 — 4 Stat Card (grid 4 kolom):**
- Total Guru (+ info berapa aktif hari ini)
- Total Siswa (+ info tambahan minggu ini)
- Ujian Aktif (yang sedang berlangsung saat ini)
- Total Mata Pelajaran (+ info jumlah jurusan)
**Baris 2 — 2 Card (grid 2 kolom, rasio 1:1):**
- Card kiri: **Penugasan Guru Terbaru** — tabel 4 kolom (Guru, Mata Pelajaran, Kelas, Status) dengan badge status
- Card kanan: **Aktivitas Terbaru** — list vertikal dengan dot berwarna dan timestamp
### Sidebar Menu Admin
```
[Dashboard]       ← aktif di halaman ini
[Guru]
[Siswa]
--- Akademik ---
[Mata Pelajaran]
[Kelas]
[Jurusan]
[Penugasan Guru]
--- (space) ---
[Pengaturan]
[Keluar]
```
 
---
 
## 4. Halaman CRUD Admin (Guru, Siswa, Mata Pelajaran, Kelas, Jurusan, Penugasan Guru)
 
Semua halaman CRUD mengikuti pola yang sama:
 
### Halaman Index (Daftar)
```
[Topbar: Judul + tombol "Tambah [Entitas]" di kanan]
[Card penuh lebar]
  [Search/Filter bar di atas tabel jika diperlukan]
  [Tabel data]
    Kolom terakhir: tombol Edit (biru) + Hapus (merah) berdampingan
  [Pagination sederhana di bawah tabel]
```
 
### Halaman Create / Edit
```
[Topbar: "Tambah [Entitas]" atau "Edit [Entitas]" + breadcrumb kecil]
[Card tunggal max-w-xl centered atau full lebar]
  [Form fields vertical]
  [Tombol: Simpan (primary) + Batal (secondary) di kanan bawah]
```
 
### Halaman Penugasan Guru (khusus)
- Tampilkan dropdown: pilih Guru → pilih Mata Pelajaran → pilih Kelas
- Di bawah form, tampilkan tabel penugasan yang sudah ada dengan tombol hapus per baris
---
 
## 5. Dashboard Guru
 
**Route:** `/guru/dashboard` → `guru.dashboard`
 
### Layout
- Sidebar kiri (navy, lebih sempit — hanya 3 menu utama) + main content
### Sidebar Menu Guru
```
[Brand + nama guru dan mapel yang diampu]
--- Menu ---
[Dashboard]
[Bank Soal]
[Hasil Ujian]
--- (space) ---
[Keluar]
```
 
### Isi Konten
 
**Baris 1 — 3 Stat Card:**
- Bank Soal Saya
- Total Soal (di semua bank soal)
- Ujian Selesai (riwayat)
**Baris 2 — 2 Card (grid, rasio ~1.4:1):**
- Card kiri: **Bank Soal Aktif** — list dengan nama bank soal, jumlah soal, kelas yang dituju, badge status (Aktif / Terjadwal / Draft). Ada tombol "+ Buat baru" di header card.
- Card kanan: **Ujian Mendatang** — list jadwal ujian dengan countdown `"mulai dalam X jam"` atau `"mulai dalam X hari"` berwarna biru
---
 
## 6. Halaman Bank Soal (Guru)
 
**Route:** `/guru/bank-soal` → index, create, show, edit
 
### Index (Daftar Bank Soal)
- Grid 2 kolom atau tabel, tampilkan: nama ujian, mata pelajaran, jumlah soal, kelas, jadwal, status
- Tombol aksi per row: Detail / Edit / Hapus
- Tombol "+ Buat Bank Soal Baru" di topbar
### Create / Edit Bank Soal
Form fields:
- Nama Ujian
- Mata Pelajaran (dropdown — hanya yang ditugaskan ke guru ini)
- Kelas yang diikutkan (multi-select atau checkbox)
- Durasi ujian (menit)
- Jadwal Mulai (datetime-local)
- Jadwal Selesai (datetime-local)
### Show (Detail Bank Soal)
Tampilkan info bank soal + tabel soal-soal yang sudah ada.
- Kolom tabel soal: No., Pertanyaan (truncate), Pilihan (jumlah), Jawaban Benar, Aksi
- Tombol "+ Tambah Soal" di atas tabel
### Form Tambah / Edit Soal
- Textarea pertanyaan (full lebar)
- 5 input pilihan jawaban (A–E)
- Radio button atau select untuk pilih jawaban benar
- Tombol Simpan + Batal
---
 
## 7. Halaman Hasil Ujian (Guru)
 
**Route:** `/guru/hasil-ujian` → index dan show
 
### Index
- Filter: pilih Bank Soal (dropdown) → tampilkan daftar siswa yang sudah mengerjakan
- Tabel: Nama Siswa, Kelas, Nilai, Waktu Mulai, Waktu Selesai, Durasi, Pelanggaran (jumlah tab switch)
- Baris dengan pelanggaran > 0 diberi highlight ringan (bg-yellow-50)
- Tombol "Lihat Detail" per baris
### Show (Detail Jawaban Siswa)
- Header: info siswa, bank soal, nilai akhir
- List soal beserta:
  - Teks pertanyaan
  - Jawaban siswa (highlight hijau jika benar, merah jika salah)
  - Jawaban benar (selalu tampilkan)
- Di atas list: ringkasan angka benar/salah
### Statistik Bank Soal (`/guru/bank-soal/{id}/statistik`)
- Per soal: persentase siswa yang menjawab benar
- Tampilkan sebagai progress bar horizontal per soal (hijau = tinggi, merah = rendah)
---
 
## 8. Dashboard Siswa → Redirect ke Ujian
 
**Route:** `/siswa/dashboard` → redirect ke `siswa.ujian.index`
- Dashboard siswa tidak memiliki tampilan sendiri, langsung redirect.
---
 
## 9. Halaman Ujian Siswa (Halaman Kode / Countdown)
 
**Route:** `/siswa/ujian` → `siswa.ujian.index`
 
### Kondisi A: Belum ada ujian aktif
```
[Layout centered, no sidebar]
[Card besar di tengah layar]
  Icon kalender besar
  Teks: "Belum ada ujian hari ini"
  Sub: mata pelajaran dan jadwal ujian berikutnya (jika ada)
  Countdown timer jika ujian < 10 menit lagi
```
 
### Kondisi B: Ujian aktif, input kode
```
[Layout centered, no sidebar]
[Card input kode]
  Judul: "Masukkan Kode Ujian"
  Sub: nama mata pelajaran dan kelas
  Input besar format XXX-XXX dengan font monospace
  Tombol "Mulai Ujian"
  Keterangan: "Kode diperbarui setiap 5 menit"
```
 
**Catatan:** Kode ujian diverifikasi via `POST /siswa/ujian/{ujian}/verify-code`.
 
---
 
## 10. Halaman Pengerjaan Ujian
 
**Route:** `/siswa/ujian/{ujian}/show` (show method)
 
### Layout
- **Tidak ada sidebar** — full screen ujian
- Topbar gelap (navy) berisi: nama ujian + timer besar
- Security bar kuning di bawah topbar: peringatan jangan pindah tab
- Area soal di kiri (flex-1)
- Panel navigasi soal di kanan (lebar fixed ~240px)
### Topbar Ujian
```
[ExamHub logo kecil] [Nama Ujian — Kelas]    [🕐 Sisa waktu: 01:23:45]
```
- Timer menggunakan font tabular (angka tidak bergeser), warna `#60A5FA`
- Ketika tersisa < 10 menit: warna timer berubah jadi merah dan berkedip pelan
### Security Bar
```
[🛡 ikon] Sesi ujian aktif — jangan berpindah tab atau halaman. Pelanggaran dicatat otomatis.
```
- Background `#FEF3C7` (kuning muda), teks `#92400E`
### Area Soal (Kiri)
- Nomor soal besar dalam lingkaran navy
- Teks pertanyaan: `text-sm leading-relaxed`
- 5 opsi jawaban (A–E):
  - Default: border gray, background putih
  - Hover: border biru, background biru muda
  - Dipilih: border biru solid, background `#EFF6FF`, lingkaran huruf terisi biru
- Footer soal: tombol "← Sebelumnya", tombol "⚑ Tandai Ragu" (tengah), tombol "Selanjutnya →"
### Panel Navigasi Soal (Kanan)
- Grid 5 kolom tombol kecil, masing-masing menampilkan nomor soal
- Warna tombol:
  - **Default** (belum dijawab): abu-abu
  - **Answered** (sudah dijawab): biru muda (`#EFF6FF`, border biru)
  - **Current** (sedang dikerjakan): navy gelap, teks putih
  - **Flagged** (ditandai ragu): kuning muda (`#FFFBEB`, border kuning)
- Legend warna di bawah grid
- Progress bar: "X/Y soal dijawab"
- Tombol "Kumpulkan Ujian" di paling bawah (full width, navy)
### Perilaku JavaScript
Implementasikan hal-hal berikut di halaman ujian:
 
1. **Timer countdown** — update setiap detik, simpan ke localStorage sebagai backup, submit otomatis jika waktu habis
2. **Auto-save jawaban** — setiap klik opsi, kirim `POST /siswa/ujian/{ujian}/answer` secara async (fetch/axios), tanpa reload halaman
3. **Tab switch detection:**
   ```javascript
   document.addEventListener('visibilitychange', () => {
     if (document.hidden) {
       // Kirim POST /siswa/ujian/{ujian}/violation
       // Tampilkan modal: "Pelanggaran terdeteksi! Masukkan kode ujian ulang"
       // Blokir interaksi sampai kode diinput ulang
     }
   });
   ```
4. **Prevent back button:**
   ```javascript
   history.pushState(null, null, location.href);
   window.addEventListener('popstate', () => {
     history.pushState(null, null, location.href);
   });
   ```
5. **Confirm sebelum submit:** Tampilkan modal konfirmasi dengan ringkasan (berapa soal belum dijawab, berapa ditandai ragu)
### Modal Konfirmasi Submit
```
[Overlay gelap]
[Card modal putih, centered]
  Judul: "Yakin ingin mengumpulkan ujian?"
  Ringkasan: "Dijawab: 28/30 soal | Ditandai ragu: 2 soal"
  Tombol: [Batal] [Kumpulkan]
```
 
### Modal Re-autentikasi Kode (setelah pelanggaran)
```
[Overlay]
[Card modal]
  Ikon peringatan merah
  Judul: "Pelanggaran Terdeteksi"
  Teks: "Anda berpindah halaman. Masukkan kode ujian untuk melanjutkan."
  Input kode XXX-XXX
  Tombol: "Verifikasi & Lanjutkan"
```
 
---
 
## 11. Halaman Selesai Ujian (Siswa)
 
Tampil setelah ujian dikumpulkan:
```
[Layout centered, no sidebar]
[Card besar]
  Ikon centang besar hijau
  Judul: "Ujian Selesai!"
  Sub: nama ujian dan waktu penyelesaian
  [Kotak info]: Waktu Pengerjaan
  Teks: "Nilai akan tersedia setelah guru mengumumkan hasil."
  Tombol: "Kembali ke Beranda"
```
- Siswa **tidak** melihat nilai atau jawaban benar dari halaman ini.
---
 
## 12. Aturan Umum untuk Semua Halaman
 
### Responsivitas
- Sidebar bisa dikollapse di layar < `lg` (1024px): tampilkan sebagai hamburger menu
- Semua tabel: gunakan `overflow-x-auto` wrapper agar tidak pecah di mobile
- Card grid: gunakan `sm:grid-cols-2 lg:grid-cols-4` dst.
### Error & Validasi
- Tampilkan error validasi Breeze/Laravel di bawah masing-masing field: `<p class="text-xs text-red-600 mt-1">{{ $message }}</p>`
- Flash message (success/error) tampilkan sebagai banner di bawah topbar:
  ```html
  <!-- Success -->
  <div class="mx-5 mt-4 px-4 py-2.5 bg-green-50 border border-green-200 rounded-lg text-xs text-green-800 flex items-center gap-2">
    ✓ Pesan sukses di sini
  </div>
  ```
 
### Empty State
Setiap tabel atau list yang bisa kosong harus punya empty state:
```html
<div class="py-12 text-center">
  <p class="text-sm text-gray-400">Belum ada data [nama entitas].</p>
  <a href="#" class="mt-2 inline-block text-xs text-blue-500">+ Tambah pertama</a>
</div>
```
 
### Pagination
Gunakan Tailwind pagination Laravel bawaan, styling dengan class `text-xs`:
```php
{{ $data->links() }}
```
Atau override dengan komponen Blade minimal.
 
### Loading State (untuk ujian)
Tombol yang melakukan submit async (save answer, verify code) harus menampilkan state loading:
- Disabled + spinner kecil saat request berlangsung
- Kembali normal setelah response
---
 
## 13. Blade Layout Structure
 
### Layout Utama (Authenticated)
`resources/views/layouts/app.blade.php`
```
<html>
  <body class="flex h-screen bg-gray-50 font-sans">
    @include('layouts.sidebar')  ← komponen sidebar sesuai role
    <div class="flex flex-col flex-1 overflow-hidden">
      @include('layouts.topbar')
      <main class="flex-1 overflow-y-auto p-5 bg-gray-50">
        @if(session('success'))
          @include('components.flash-success')
        @endif
        @yield('content')
      </main>
    </div>
  </body>
</html>
```
 
### Layout Ujian (Full Screen, no sidebar)
`resources/views/layouts/exam.blade.php`
```
<html>
  <body class="bg-gray-100 font-sans">
    @yield('content')
  </body>
</html>
```
 
### Layout Auth (Login)
`resources/views/layouts/auth.blade.php`
```
<html>
  <body class="min-h-screen bg-gray-50 font-sans flex items-center justify-center">
    @yield('content')
  </body>
</html>
```
 
---
 
## 14. Komponen Blade yang Perlu Dibuat
 
| Nama File | Deskripsi |
|---|---|
| `components/sidebar-admin.blade.php` | Sidebar khusus Admin |
| `components/sidebar-guru.blade.php` | Sidebar khusus Guru |
| `components/topbar.blade.php` | Topbar universal dengan slot judul |
| `components/stat-card.blade.php` | Card statistik angka (label, angka, chip) |
| `components/badge.blade.php` | Badge status (prop: type = success/warning/info/danger) |
| `components/flash-message.blade.php` | Banner notifikasi sukses/error |
| `components/empty-state.blade.php` | Tampilan kosong untuk tabel/list |
| `components/confirm-modal.blade.php` | Modal konfirmasi generic |
 
---
 
## 15. Checklist Refactor per Halaman
 
Gunakan checklist ini untuk memastikan setiap halaman sudah direfactor:
 
- [ ] `auth/login.blade.php` — split panel, form, role pills
- [ ] `admin/dashboard.blade.php` — 4 stat cards + 2 cards baris kedua
- [ ] `admin/guru/index.blade.php` — tabel + tombol + search
- [ ] `admin/guru/create.blade.php` — form create
- [ ] `admin/guru/edit.blade.php` — form edit
- [ ] `admin/siswa/index.blade.php`
- [ ] `admin/siswa/create.blade.php`
- [ ] `admin/siswa/edit.blade.php`
- [ ] `admin/mata-pelajaran/index.blade.php`
- [ ] `admin/mata-pelajaran/create.blade.php`
- [ ] `admin/mata-pelajaran/edit.blade.php`
- [ ] `admin/kelas/index.blade.php`
- [ ] `admin/kelas/create.blade.php`
- [ ] `admin/kelas/edit.blade.php`
- [ ] `admin/jurusan/index.blade.php`
- [ ] `admin/jurusan/create.blade.php`
- [ ] `admin/jurusan/edit.blade.php`
- [ ] `admin/penugasan-guru/index.blade.php`
- [ ] `admin/penugasan-guru/create.blade.php`
- [ ] `admin/penugasan-guru/edit.blade.php`
- [ ] `guru/dashboard.blade.php` — 3 stat cards + 2 cards baris kedua
- [ ] `guru/bank-soal/index.blade.php`
- [ ] `guru/bank-soal/create.blade.php`
- [ ] `guru/bank-soal/show.blade.php` — daftar soal
- [ ] `guru/bank-soal/edit.blade.php`
- [ ] `guru/soal/create.blade.php`
- [ ] `guru/soal/edit.blade.php`
- [ ] `guru/hasil-ujian/index.blade.php`
- [ ] `guru/hasil-ujian/show.blade.php`
- [ ] `guru/statistik.blade.php`
- [ ] `siswa/ujian/index.blade.php` — kondisi A & B
- [ ] `siswa/ujian/show.blade.php` — halaman pengerjaan ujian (full screen)
- [ ] `siswa/ujian/selesai.blade.php` — halaman hasil
---
 
## 16. Catatan Khusus
 
1. **Jangan ubah logika PHP/Controller** — refactor hanya pada tampilan Blade dan styling Tailwind.
2. **Pertahankan semua `@csrf`, `method('PATCH')`, `method('DELETE')`** di dalam form.
3. **Pertahankan semua route name** yang sudah ada di `web.php` (admin.guru.index, guru.bank-soal.show, dsb.).
4. **Variabel yang dikirim controller** tetap digunakan dengan nama yang sama — jangan ganti nama variabel Blade.
5. Untuk halaman ujian, **JavaScript harus inline** dalam `@push('scripts')` atau `<script>` di bawah `@endsection` — jangan pisah ke file eksternal kecuali ada `vite.config.js` yang mendukung.
6. **Tailwind CDN** sudah diinstall via Breeze — tidak perlu tambahan konfigurasi.
7. Untuk icon, gunakan **Heroicons** (sudah tersedia di ekosistem Breeze/Tailwind) atau SVG inline — jangan tambahkan library icon baru kecuali sudah ada di package.json.