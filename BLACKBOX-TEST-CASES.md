# Blackbox Test Case - Sistem Ujian Online (SIBUO)

Dokumen ini berisi daftar skenario pengujian manual (Blackbox Testing) untuk semua fitur dan role dalam aplikasi SIBUO.

## 1. Modul: Autentikasi & Otorisasi
| No | Modul | Test Scenario | Test Cases | Roles | Data Test | Test Step | Expected Result | Actual Result | Status | Notes |
|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|
| 1 | Auth | Login Valid | Melakukan login dengan kredensial benar | Semua | Email & Pass valid | 1. Buka halaman login. 2. Input email & password. 3. Klik Login. | Masuk ke Dashboard sesuai Role. | | | |
| 2 | Auth | Login Invalid | Melakukan login dengan password salah | Semua | Email valid, Pass salah | 1. Input email & pass salah. 2. Klik Login. | Muncul pesan error "These credentials do not match our records." | | | |
| 3 | Auth | Role Access | Mencoba akses admin dengan akun siswa | Siswa | Akun Siswa | 1. Login sebagai Siswa. 2. Ketik URL `/admin/guru` di browser. | Muncul error 403 (Unauthorized). | | | |
| 4 | Auth | Logout | Melakukan logout dari sistem | Semua | - | 1. Klik menu Profile. 2. Klik Log Out. | Sesi berakhir dan diarahkan ke halaman Welcome/Login. | | | |

## 2. Modul: Administrator (Manajemen Data)
| No | Modul | Test Scenario | Test Cases | Roles | Data Test | Test Step | Expected Result | Actual Result | Status | Notes |
|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|
| 5 | Admin | CRUD Guru | Menambah data Guru baru | Admin | Data Guru baru | 1. Menu Guru -> Create. 2. Isi data & simpan. | Guru baru muncul di daftar. | | | |
| 6 | Admin | CRUD Siswa | Menambah Siswa dengan Kelas/Jurusan | Admin | Data Siswa, Kelas X | 1. Menu Siswa -> Create. 2. Pilih Kelas & Jurusan. 3. Simpan. | Siswa tersimpan dengan relasi kelas yang benar. | | | |
| 7 | Admin | Penugasan | Menghubungkan Guru ke Mapel & Kelas | Admin | Guru A, Mapel B, Kelas C | 1. Menu Penugasan -> Create. 2. Pilih Guru, Mapel, Kelas. 3. Simpan. | Guru A berhak membuat Bank Soal untuk Mapel B. | | | |

## 3. Modul: Guru (Manajemen Ujian)
| No | Modul | Test Scenario | Test Cases | Roles | Data Test | Test Step | Expected Result | Actual Result | Status | Notes |
|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|
| 8 | Guru | Bank Soal | Membuat Bank Soal baru | Guru | Nama Bank, Durasi 60m | 1. Menu Bank Soal -> Create. 2. Isi jadwal & durasi. 3. Simpan. | Bank soal berhasil dibuat. | | | |
| 9 | Guru | Soal | Menambah butir soal pilihan ganda | Guru | Pertanyaan, Opsi A-D | 1. Klik Bank Soal -> Soal. 2. Tambah Soal. 3. Isi pertanyaan & kunci. | Soal muncul di daftar Bank Soal. | | | |
| 10 | Guru | Monitoring | Cek Kode Ujian Aktif | Guru | - | 1. Buka Dashboard Guru. 2. Cek kolom Kode Ujian. | Kode XXX-XXX muncul dan berubah tiap 5 menit. | | | |

## 4. Modul: Siswa (Pengerjaan Ujian)
| No | Modul | Test Scenario | Test Cases | Roles | Data Test | Test Step | Expected Result | Actual Result | Status | Notes |
|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|
| 11 | Siswa | Dashboard | Cek ketersediaan ujian | Siswa | - | 1. Buka Dashboard Siswa. | Muncul daftar ujian sesuai Kelas & Jadwal. | | | |
| 12 | Siswa | Countdown | Cek timer mundur ujian mendatang | Siswa | - | 1. Lihat daftar "Ujian Mendatang". | Muncul teks "Mulai dalam: Xm Yd" yang terus update. | | | |
| 13 | Siswa | Entry Ujian | Input Kode Ujian salah | Siswa | Kode ngawur | 1. Klik Mulai Ujian. 2. Input kode salah. | Muncul error "Kode tidak valid". | | | |
| 14 | Siswa | Entry Ujian | Input Kode Ujian benar | Siswa | Kode dari Dashboard Guru | 1. Input kode yang sedang aktif. 2. Klik Mulai. | Masuk ke halaman pengerjaan soal. | | | |
| 15 | Siswa | Ujian | Cek Randomisasi Soal | Siswa | - | 1. Login dengan 2 akun Siswa berbeda. 2. Bandingkan Soal No.1. | Urutan soal berbeda antar Siswa. | | | |
| 16 | Siswa | Auto-Save | Cek penyimpanan jawaban otomatis | Siswa | Pilih Opsi A | 1. Pilih jawaban. 2. Refresh halaman. | Jawaban tetap terpilih (tidak reset). | | | |
| 17 | Siswa | Security | Deteksi Pindah Tab (Blocked) | Siswa | Buka Tab Baru | 1. Sedang ujian, klik tab baru di browser. 2. Kembali ke tab ujian. | Layar Merah "AKSES DIBLOKIR". | | | |
| 18 | Siswa | Security | Buka Blokir (Re-auth) | Siswa | Kode Ujian Aktif | 1. Layar terblokir. 2. Input kode ujian aktif. | Blokir terbuka, bisa lanjut mengerjakan. | | | |
| 19 | Siswa | Security | Pencatatan Pelanggaran | Siswa | Pindah Tab | 1. Lakukan pindah tab. 2. Cek database/halaman hasil guru. | Kolom 'pelanggaran' bertambah (+1). | | | |
| 20 | Siswa | Selesai | Submit Ujian | Siswa | - | 1. Klik Selesaikan Ujian. 2. Cek ringkasan. 3. Klik Kirim. | Ujian selesai, diarahkan ke halaman info "Telah Selesai". | | | |

## 5. Modul: Laporan & Hasil (Monitoring Guru)
| No | Modul | Test Scenario | Test Cases | Roles | Data Test | Test Step | Expected Result | Actual Result | Status | Notes |
|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|:---|
| 21 | Guru | Hasil | Cek Waktu Penyelesaian | Guru | - | 1. Menu Hasil Ujian. 2. Cek kolom Waktu. | Waktu tampil positif (X menit Y detik). | | | |
| 22 | Guru | Hasil | Cek Detail Jawaban & Skor | Guru | - | 1. Klik Lihat Detail pada salah satu siswa. | Muncul skor, jumlah benar, dan detail per soal. | | | |
| 23 | Siswa | Privasi | Cek visibility skor siswa | Siswa | - | 1. Ujian selesai. 2. Lihat halaman detail. | Skor TIDAK tampil bagi Siswa. | | | |
