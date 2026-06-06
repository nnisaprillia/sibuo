# Testing Report - Sistem Ujian Online (SIBUO)
**Tanggal:** 6 Juni 2026
**Status Keseluruhan:** ✅ LULUS (Dengan Catatan)

## 1. Pendahuluan
Laporan ini merangkum hasil pengujian Fase 8 untuk aplikasi SIBUO. Pengujian difokuskan pada alur kerja utama (Core Workflow) mulai dari administrasi, pembuatan soal oleh guru, hingga pengerjaan ujian oleh siswa dengan fitur keamanan terbaru.

---

## 2. Cakupan Pengujian

### A. Authentication & Authorization (Role-Based Access)
- **Login Redirect:** User diarahkan ke dashboard yang sesuai (Admin, Guru, Siswa).
- **Middleware Protection:** Role Siswa tidak bisa mengakses route `/admin/*` atau `/guru/*`.
- **Hasil:** ✅ Berhasil. Middleware `RoleMiddleware` berfungsi dengan benar membatasi akses antar role.

### B. Fitur Admin (Manajemen Data)
- **CRUD Guru/Siswa/Kelas/Jurusan:** Pengetesan input data valid dan invalid (email duplikat, dll).
- **Penugasan Guru:** Menugaskan guru ke mata pelajaran dan kelas tertentu.
- **Hasil:** ✅ Berhasil. Relasi data tersimpan dengan benar di tabel `penugasan_guru`.

### C. Fitur Guru (Bank Soal & Monitoring)
- **Pembuatan Bank Soal:** Hanya bisa membuat bank soal untuk mata pelajaran yang ditugaskan.
- **Manajemen Soal:** CRUD soal (A, B, C, D) dengan kunci jawaban.
- **Monitoring Hasil:** Melihat daftar siswa yang sudah mengerjakan.
- **Kejanggalan Ditemukan:** 
    - ⚠️ **Waktu Penyelesaian Negatif:** Sebelumnya ditemukan nilai negatif pada waktu penyelesaian. **Status: SUDAH DIPERBAIKI** menggunakan fungsi `abs()`.
    - ⚠️ **Visibility Skor:** Sebelumnya nilai muncul di index guru (berisiko terlihat siswa lain). **Status: SUDAH DIPERBAIKI**, nilai hanya ada di halaman detail.

### D. Fitur Siswa & Keamanan Ujian (Core Feature)
- **Validasi Kode Ujian:** Input kode XXX-XXX wajib ada dan harus cocok dengan kode aktif.
- **Rotasi Kode:** Kode berganti tiap 5 menit (berhasil).
- **Randomisasi Soal:** Urutan soal berbeda antar siswa (menggunakan `ujian_id` sebagai seed).
- **Auto-Save:** Jawaban tersimpan otomatis via AJAX saat berpindah soal atau memilih opsi.
- **Tab-Switching Security:**
    - Jika pindah tab -> Layar diblokir (Overlay Merah).
    - Harus input kode ujian ulang untuk lanjut.
    - Pelanggaran dicatat ke database.
- **Hasil:** ✅ Berhasil. Fitur keamanan bekerja sangat ketat.

---

## 3. Detail Kejanggalan & Observasi

| Fitur | Temuan/Kejanggalan | Status | Solusi yang Diambil |
| :--- | :--- | :--- | :--- |
| **Timer Ujian** | Error `Undefined variable $expireAt` pada view. | ✅ Fixed | Variabel dihitung di Controller dan dikirim secara eksplisit ke View. |
| **Penyelesaian** | Waktu penyelesaian tersimpan negatif. | ✅ Fixed | Menggunakan `abs(now()->diffInSeconds($waktu_mulai))` untuk menjamin angka positif. |
| **Rotasi Kode** | Kode tetap berotasi meskipun jadwal ujian sudah selesai. | ✅ Fixed | Modifikasi Model `BankSoal` agar berhenti regenerasi jika melewati `jadwal_selesai`. |
| **Keamanan** | Siswa bisa menekan tombol back browser. | ✅ Mitigated | JavaScript `pushState` digunakan untuk menahan siswa tetap di halaman ujian. |
| **Pelanggaran** | Siswa bingung kenapa layar jadi merah. | ✅ Info Added | Menambahkan pesan instruksi yang jelas pada modal pemblokiran. |

---

## 4. Rekomendasi Deployment
Aplikasi secara fungsional sudah sangat stabil. Keamanan ujian (anti-nyontek) sudah berada di level yang sangat baik dengan deteksi tab-switching dan rotasi kode.

**Rekomendasi Tambahan:**
1. Pastikan server produksi memiliki zona waktu (Timezone) yang sinkron dengan aplikasi agar timer tidak melompat.
2. Gunakan HTTPS agar fitur JavaScript `visibilitychange` dan `pushState` berjalan optimal di semua browser modern.

---
**Penguji:** Gemini CLI Agent
**Fase:** 8 - Quality Assurance Finished.
