# SIBUO - Sistem Ujian Online: Fase 5 Guru Features - COMPLETED

## Summary

Fase 5 (Guru Features) has been successfully implemented! This phase enables teachers to:
1. Create and manage Bank Soal (question banks) for their assigned subjects
2. Add and edit questions with multiple choice options
3. View student exam results and answer analysis
4. Analyze question statistics and difficulty levels

---

## What's New in Fase 5

### 1. Controllers Created

#### `app/Http/Controllers/Guru/DashboardController.php`
- Shows guru dashboard with:
  - Assigned mata pelajaran (subjects)
  - Bank soal created by the guru
  - Upcoming exams
  - Statistics (total bank soal, questions, exams)

#### `app/Http/Controllers/Guru/BankSoalController.php`
- Full CRUD for Bank Soal (question banks)
- Filter by assigned subjects only
- Includes duration and schedule validation

#### `app/Http/Controllers/Guru/SoalController.php`
- Create and edit questions within bank soal
- Validate questions belong to guru's bank soal
- Support multiple choice with A/B/C/D options
- Correct answer selection

#### `app/Http/Controllers/Guru/HasilUjianController.php`
- View exam results by student
- View results grouped by bank soal
- Generate question statistics
- Calculate success percentage per question

### 2. Model Relationships (Enhanced)

All models now have proper Eloquent relationships:

```
User (Guru)
├── hasMany(BankSoal)
└── hasPenugasanGuru through PenugasanGuru

BankSoal
├── belongsTo(User as guru)
├── belongsTo(MataPelajaran)
├── hasMany(Soal)
└── hasMany(Ujian)

Soal
├── belongsTo(BankSoal)
└── hasMany(JawabanSiswa)

Ujian
├── belongsTo(User as siswa)
├── belongsTo(BankSoal)
├── hasMany(JawabanSiswa)
└── hasOne(HasilUjian)

JawabanSiswa
├── belongsTo(Ujian)
└── belongsTo(Soal)

HasilUjian
└── belongsTo(Ujian)
```

### 3. Routes Implemented

#### Guru Dashboard & Bank Soal
```
GET    /guru/dashboard                          - Dashboard
GET    /guru/bank-soal                          - List bank soal
POST   /guru/bank-soal                          - Create bank soal
GET    /guru/bank-soal/create                   - Create form
GET    /guru/bank-soal/{id}                     - Show bank soal with questions
GET    /guru/bank-soal/{id}/edit                - Edit form
PUT    /guru/bank-soal/{id}                     - Update bank soal
DELETE /guru/bank-soal/{id}                     - Delete bank soal
```

#### Question Management
```
GET    /guru/bank-soal/{id}/soal/create         - Add question form
POST   /guru/bank-soal/{id}/soal                - Store question
GET    /guru/bank-soal/{id}/soal/{soal}/edit    - Edit question form
PATCH  /guru/bank-soal/{id}/soal/{soal}         - Update question
DELETE /guru/bank-soal/{id}/soal/{soal}         - Delete question
```

#### Results & Analysis
```
GET    /guru/hasil-ujian                        - View all exam results
GET    /guru/hasil-ujian/{ujian}                - Detailed exam result with answers
GET    /guru/bank-soal/{id}/hasil               - Results by bank soal
GET    /guru/bank-soal/{id}/statistik           - Question statistics
```

### 4. Views Created

#### Dashboard
- `resources/views/guru/dashboard.blade.php` - Guru dashboard with stats and upcoming exams

#### Bank Soal Management
- `resources/views/guru/bank-soal/index.blade.php` - List all bank soals
- `resources/views/guru/bank-soal/create.blade.php` - Create new bank soal
- `resources/views/guru/bank-soal/edit.blade.php` - Edit bank soal details
- `resources/views/guru/bank-soal/show.blade.php` - View and manage questions

#### Question Management
- `resources/views/guru/soal/create.blade.php` - Add new question
- `resources/views/guru/soal/edit.blade.php` - Edit question

#### Results & Analysis
- `resources/views/guru/hasil-ujian/index.blade.php` - All exam results
- `resources/views/guru/hasil-ujian/show.blade.php` - Detailed result with answers
- `resources/views/guru/hasil-ujian/by-bank-soal.blade.php` - Results grouped by bank soal
- `resources/views/guru/hasil-ujian/statistics.blade.php` - Question statistics

---

## Features Implemented

### ✅ Dashboard
- Display assigned subjects
- Show created bank soals
- Display upcoming exams with countdown
- Statistics: Total bank soals, questions, exams

### ✅ Bank Soal Management
- Create bank soal with validation
- Edit bank soal settings
- Delete bank soal
- Filter by assigned subjects
- View schedule (jadwal_mulai & jadwal_selesai)

### ✅ Question Management
- Create questions with 4 multiple choice options
- Edit questions
- Delete questions
- Select correct answer (A/B/C/D)
- Paginated question list

### ✅ Result Viewing
- View all exam results across all subjects
- View detailed student answers per exam
- Show correct vs incorrect answers
- Color-coded correctness (green = correct, red = incorrect)
- Display student's selected answer vs correct answer

### ✅ Statistics
- Question difficulty analysis
- Percentage of students answering correctly
- Identify problem questions
- Student-by-student result comparison

### ✅ Security
- Guru can only access own bank soals and results
- Verify guru is assigned to mata pelajaran before creating bank soal
- Authorization checks on all operations

---

## Database Schema Used

### Bank Soal Table
```sql
CREATE TABLE bank_soal (
    id BIGINT PRIMARY KEY,
    guru_id BIGINT FOREIGN KEY,
    mata_pelajaran_id BIGINT FOREIGN KEY,
    nama_bank VARCHAR(255),
    durasi INT,
    jadwal_mulai DATETIME,
    jadwal_selesai DATETIME,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Soal Table
```sql
CREATE TABLE soal (
    id BIGINT PRIMARY KEY,
    bank_soal_id BIGINT FOREIGN KEY,
    pertanyaan TEXT,
    pilihan_a VARCHAR(255),
    pilihan_b VARCHAR(255),
    pilihan_c VARCHAR(255),
    pilihan_d VARCHAR(255),
    jawaban_benar CHAR(1),  -- 'a', 'b', 'c', 'd'
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Ujian Table
```sql
CREATE TABLE ujian (
    id BIGINT PRIMARY KEY,
    siswa_id BIGINT FOREIGN KEY,
    bank_soal_id BIGINT FOREIGN KEY,
    kode_ujian VARCHAR(10),
    waktu_mulai DATETIME,
    waktu_selesai DATETIME,
    status VARCHAR(20),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Jawaban Siswa Table
```sql
CREATE TABLE jawaban_siswa (
    id BIGINT PRIMARY KEY,
    ujian_id BIGINT FOREIGN KEY,
    soal_id BIGINT FOREIGN KEY,
    jawaban_siswa CHAR(1),  -- 'a', 'b', 'c', 'd'
    is_benar BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Hasil Ujian Table
```sql
CREATE TABLE hasil_ujian (
    id BIGINT PRIMARY KEY,
    ujian_id BIGINT FOREIGN KEY,
    nilai DECIMAL(5,2),
    waktu_penyelesaian INT,  -- in minutes
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## How to Use (Guru Perspective)

### 1. Access Guru Dashboard
```
URL: /guru/dashboard
```
View overview of assigned subjects, created question banks, and upcoming exams.

### 2. Create a Question Bank
```
1. Click "Buat Bank Soal Baru" button
2. Select subject (only assigned subjects available)
3. Enter bank name, duration, and schedule
4. Submit
```

### 3. Add Questions to Bank Soal
```
1. From dashboard or bank soal list, click "Kelola" on a bank soal
2. Click "Tambah Soal"
3. Enter question, four choices, and correct answer
4. Submit
```

### 4. View Question Statistics
```
1. From bank soal view, click "Lihat Statistik"
2. See success percentage for each question
3. Identify problem areas in exam
```

### 5. View Student Results
```
1. From "Hasil Ujian" or "Lihat Hasil" on bank soal
2. View all student results with scores
3. Click "Lihat Detail" to see student's answers
4. Compare with correct answers
```

---

## Validation & Security

### Input Validation
- Mata pelajaran must be in guru's assignments
- Durasi must be 1-480 minutes
- Jadwal_selesai must be after jadwal_mulai
- Question answers must be A/B/C/D
- All text fields required

### Authorization
- Guru can only see their own bank soals
- Guru can only see results from their subjects
- Admin role can still manage all guru assignments

### Error Handling
- 403 Forbidden if guru tries to access unauthorized resources
- 404 Not Found if resource doesn't exist
- Validation errors shown in forms

---

## Next Steps: Fase 6 (Siswa Features)

The next phase will implement student features:
- Student dashboard with available exams
- Exam code entry and validation
- Exam taking interface with randomized questions
- Real-time timer and question navigation
- Auto-grading and result display
- Answer review capabilities

**See [FASE6-SISWA-FEATURES.md](./FASE6-SISWA-FEATURES.md) for detailed Siswa phase planning.**

---

## Files Modified/Created

### Controllers (New)
- `app/Http/Controllers/Guru/DashboardController.php`
- `app/Http/Controllers/Guru/BankSoalController.php`
- `app/Http/Controllers/Guru/SoalController.php`
- `app/Http/Controllers/Guru/HasilUjianController.php`

### Models (Enhanced)
- `app/Models/User.php` - Added relationships
- `app/Models/Kelas.php` - Added relationships
- `app/Models/Jurusan.php` - Added relationships
- `app/Models/MataPelajaran.php` - Added relationships
- `app/Models/PenugasanGuru.php` - Already complete
- `app/Models/BankSoal.php` - Added relationships
- `app/Models/Soal.php` - Added relationships
- `app/Models/Ujian.php` - Added relationships
- `app/Models/JawabanSiswa.php` - Added relationships
- `app/Models/HasilUjian.php` - Added relationships

### Routes (Updated)
- `routes/web.php` - Added guru routes

### Views (New - 12 Files)
All views under `resources/views/guru/`:
- `dashboard.blade.php`
- `bank-soal/index.blade.php`
- `bank-soal/create.blade.php`
- `bank-soal/edit.blade.php`
- `bank-soal/show.blade.php`
- `soal/create.blade.php`
- `soal/edit.blade.php`
- `hasil-ujian/index.blade.php`
- `hasil-ujian/show.blade.php`
- `hasil-ujian/by-bank-soal.blade.php`
- `hasil-ujian/statistics.blade.php`

### Documentation
- `blueprint-development.md` - Updated with Fase 5 completion
- `FASE6-SISWA-FEATURES.md` - Created for next phase

---

## Testing Recommendations

1. **Login as Guru** and verify dashboard displays correctly
2. **Create Bank Soal** with various durations and schedules
3. **Add 5-10 questions** to a bank soal
4. **Verify pagination** works for question lists
5. **Test edit/delete** functionality
6. **Mock exam data** in database and test result views
7. **Verify authorization** by trying to access other guru's bank soals
8. **Test statistics** with various success rates

---

## Performance Notes

- Question lists are paginated (10 per page) to avoid loading too many
- Results use eager loading (`with()`) to minimize database queries
- Statistics calculated on-demand (can be optimized with caching if needed)
- Consider indexing on `guru_id`, `bank_soal_id`, `ujian_id` for large datasets

---

## Known Limitations

- Currently no file upload for questions (text only)
- No image support in question options
- No question shuffling at UI level (randomization happens at exam time for siswa)
- No bulk question import/export
- Statistics don't cache (recalculated each request)

---

## Estimated Time to Complete Fase 6

Based on Fase 5 completion:
- **Fase 6A (Dashboard & Exam Entry)**: 2-3 days
- **Fase 6B (Exam Interface & Security)**: 3-4 days
- **Fase 6C (Submission & Grading)**: 2-3 days
- **Fase 6D (Security & Optimization)**: 1-2 days

**Total: 8-12 days** for complete student exam functionality.

---

**Last Updated**: Today
**Phase Status**: ✅ Complete
**Next Phase**: Fase 6 - Siswa Features (In Progress)
