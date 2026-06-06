# Blueprint Development - Sistem Ujian Online Laravel 13

## Pendahuluan

Blueprint ini merancang pengembangan sistem ujian online berbasis web menggunakan Laravel versi 13 dengan Auth Breeze. Sistem ini memiliki tiga role utama: Admin, Guru, dan Siswa, dengan hak akses spesifik untuk masing-masing. Fitur tambahan termasuk randomisasi soal, validasi ujian real-time, dan pengamanan sesi ujian.

### Teknologi Utama
- **Framework**: Laravel 13
- **Authentication**: Laravel Breeze
- **Database**: MySQL/PostgreSQL
- **Frontend**: Blade Templates dengan Tailwind CSS
- **JavaScript**: Vanilla JS atau Alpine.js (dari Breeze)

### Role dan Hak Akses
1. **Admin**: CRUD untuk Guru, Siswa, Mata Pelajaran, Kelas, Jurusan; Dashboard; Penugasan Guru ke Mata Pelajaran.
2. **Guru**: Membuat Bank Soal per Mata Pelajaran; Melihat Hasil Ujian dan Jawaban Siswa.
3. **Siswa**: Mengerjakan Ujian; Tidak melihat jawaban atau nilai.

### Fitur Tambahan
- Randomisasi soal per siswa
- Validasi kelas/jurusan dan redirect otomatis ke ujian/countdown
- Kode ujian dengan format XXX-XXX (regenerate 5 menit)
- Pengamanan sesi ujian (logout otomatis jika keluar tab/halaman)

## Fase Pengembangan

### Fase 1: Planning dan Setup Proyek
- [x] Analisis kebutuhan lengkap berdasarkan spesifikasi
- [ ] Desain database schema (ERD)
- [x] Setup proyek Laravel 13 baru atau upgrade dari versi lama
- [x] Install dan konfigurasi Laravel Breeze
- [x] Konfigurasi database dan environment
- [x] Setup struktur folder untuk models, controllers, views
- [x] Install package tambahan (jika diperlukan, seperti untuk randomisasi atau timer)

### Fase 2: Database Design dan Migration
- [x] Buat migration untuk tabel users (extend dengan role, kelas, jurusan)
- [x] Buat migration untuk tabel mata_pelajaran
- [x] Buat migration untuk tabel kelas
- [x] Buat migration untuk tabel jurusan (opsional)
- [x] Buat migration untuk tabel penugasan_guru (guru_id, mata_pelajaran_id, kelas_id)
- [x] Buat migration untuk tabel bank_soal (mata_pelajaran_id, guru_id, durasi, jadwal_mulai, jadwal_selesai)
- [x] Buat migration untuk tabel soal (bank_soal_id, pertanyaan, pilihan_jawaban, jawaban_benar)
- [x] Buat migration untuk tabel ujian (siswa_id, bank_soal_id, kode_ujian, waktu_mulai, waktu_selesai, status)
- [x] Buat migration untuk tabel jawaban_siswa (ujian_id, soal_id, jawaban_siswa, benar/salah)
- [x] Buat migration untuk tabel hasil_ujian (ujian_id, nilai, waktu_penyelesaian)
- [x] Jalankan migration dan buat seeder untuk data awal
- [x] Definisikan relationships pada semua model Eloquent

### Fase 3: Authentication dan Authorization
- [x] Konfigurasi role-based access control (middleware RoleMiddleware sudah ada)
- [x] Update User model dengan relasi ke role, kelas, jurusan
- [x] Buat seeder untuk roles (Admin, Guru, Siswa)
- [x] Implementasi login redirect berdasarkan role
- [x] Buat middleware untuk proteksi route per role
- [x] Update registration untuk assign role default (Siswa)
- [x] Implementasi logout dan session management

### Fase 4: Fitur Admin
- [x] Buat CRUD Controller untuk Guru
- [x] Buat views dan routes untuk CRUD Guru
- [x] Buat CRUD Controller untuk Siswa
- [x] Buat views dan routes untuk CRUD Siswa
- [x] Buat CRUD Controller untuk Mata Pelajaran
- [x] Buat views dan routes untuk CRUD Mata Pelajaran
- [x] Buat CRUD Controller untuk Kelas
- [x] Buat views dan routes untuk CRUD Kelas
- [x] Buat CRUD Controller untuk Jurusan
- [x] Buat views dan routes untuk CRUD Jurusan
- [x] Implementasi Dashboard Admin (statistik pengguna, ujian, dll.)
- [x] Buat sistem penugasan Guru ke Mata Pelajaran per Kelas
- [x] Buat views untuk manage penugasan
- [x] Validasi dan error handling untuk semua CRUD operations

### Fase 5: Fitur Guru
- [x] Buat Controller untuk Bank Soal
- [x] Buat views untuk membuat/mengelola Bank Soal per Mata Pelajaran yang ditugaskan
- [x] Implementasi CRUD Soal dalam Bank Soal
- [x] Buat views untuk input soal (pertanyaan, pilihan, jawaban benar)
- [x] Implementasi auto-grading untuk jawaban siswa
- [x] Buat Controller untuk melihat hasil ujian siswa
- [x] Buat views untuk display nilai dan detail jawaban per siswa
- [x] Buat views untuk melihat jawaban benar/salah per mata pelajaran
- [x] Validasi akses hanya untuk mata pelajaran yang ditugaskan
- [x] Guru Dashboard dengan statistik dan ujian mendatang
- [x] View statistik soal dengan analisis persentase jawaban benar

### Fase 6: Fitur Siswa
- [x] Buat Controller untuk halaman dashboard siswa
- [x] Implementasi validasi kelas/jurusan saat login
- [x] Cek mata pelajaran yang sedang berlangsung
- [x] Implementasi redirect otomatis ke ujian atau countdown
- [x] Buat logic countdown (tampilkan jika <10 menit)
- [x] Buat Controller untuk halaman ujian
- [x] Implementasi input kode ujian (format XXX-XXX)
- [x] Generate kode ujian setiap 5 menit
- [x] Implementasi randomisasi soal per siswa
- [x] Buat logic penyimpanan jawaban real-time
- [x] Implementasi timer ujian
- [x] Pengamanan sesi (logout jika keluar tab/halaman)
- [x] Prevent back button dan refresh selama ujian

### Fase 7: Fitur Tambahan dan Optimisasi
- [x] Implementasi randomisasi soal (acak urutan per siswa)
- [x] Sistem kode ujian dengan regenerate 5  
- [x] Validasi ujian real-time dan redirect
- [x] Countdown timer untuk ujian mendatang
- [x] Pengamanan sesi ujian (JavaScript untuk detect tab switch)
- [x] Sistem Re-autentikasi (Input kode ulang jika keluar tab)
- [x] Tracking Pelanggaran (Catat jumlah pindah tab ke database)
- [x] Optimisasi performa untuk randomisasi soal
- [x] Implementasi caching untuk data sering diakses
- [x] Error handling dan logging
- [x] Responsive design untuk mobile

### Fase 8: Testing dan Quality Assurance
- [x] Unit testing untuk models dan logic bisnis
- [x] Feature testing untuk CRUD operations
- [x] Testing authentication dan authorization
- [x] Testing randomisasi soal
- [x] Testing timer dan kode ujian
- [x] Testing pengamanan sesi
- [x] Cross-browser testing
- [x] Performance testing untuk concurrent users
- [x] Security testing (SQL injection, XSS, CSRF)

### Fase 9: Deployment dan Maintenance
- [ ] Setup production environment
- [ ] Konfigurasi web server (Apache/Nginx)
- [ ] Database optimization dan backup
- [ ] SSL certificate setup
- [ ] Monitoring dan logging setup
- [ ] Dokumentasi API (jika ada)
- [ ] User manual dan training
- [ ] Post-deployment testing
- [ ] Monitoring performa dan bug fixes

## Timeline Estimasi
- **Fase 1-2**: 1-2 minggu (Planning dan Database)
- **Fase 3**: 1 minggu (Auth & Auth)
- **Fase 4**: 2-3 minggu (Admin Features)
- **Fase 5**: 2 minggu (Guru Features)
- **Fase 6**: 3 minggu (Siswa Features)
- **Fase 7**: 1-2 minggu (Optimisasi)
- **Fase 8**: 1 minggu (Testing)
- **Fase 9**: 1 minggu (Deployment)

Total estimasi: 12-17 minggu tergantung kompleksitas dan tim.

## Catatan Penting
- Pastikan semua fitur diuji secara menyeluruh sebelum deployment
- Implementasikan backup database otomatis
- Monitor performa selama ujian bersamaan
- Siapkan contingency plan untuk gangguan teknis
- Dokumentasikan semua perubahan dan fitur