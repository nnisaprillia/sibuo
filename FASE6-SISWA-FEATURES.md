# Fase 6: Fitur Siswa - Development Plan & Implementation Guide

## Overview
Implement student features for the online exam system. Students can:
- View assigned exams (Bank Soal)
- Enter exam with kode ujian (exam code)
- Take exams with randomized questions
- Receive real-time validation and auto-grading
- View results after exam completion

## Key Features

### 1. Student Dashboard
- Display available exams based on student's class
- Show upcoming exams (with countdown if < 10 minutes)
- Show exam history and results
- Quick stats on exam attempts and average scores

### 2. Exam Submission Interface
- Kode Ujian (Exam Code) input form with format XXX-XXX
- Validation of:
  - Exam code validity (regenerated every 5 minutes)
  - Student's class matches exam's class
  - Student's subject assignment
  - Exam schedule (must be between jadwal_mulai and jadwal_selesai)
- Redirect to exam if valid

### 3. Exam Interface
- **Question Display**: Randomized per student (not sequential)
- **Answer Submission**: Radio buttons for A/B/C/D choices
- **Timer**: Shows remaining time, updates in real-time
- **Navigation**: Next/Previous buttons (can review answers)
- **Auto-save**: Save answers after each selection
- **Security**:
  - Detect tab/window changes (logout if user switches tab)
  - Prevent back button during exam
  - Prevent right-click/inspect element
  - Session validation on each request

### 4. Result & Grading
- Auto-grade upon exam submission
- Display:
  - Total score
  - Number correct/incorrect
  - Time taken
  - List of answers with correct answers (if teacher allows)
- Store results in `hasil_ujian` table

## Database/Model References

### Relevant Models
- `Ujian`: Exam instance - siswa_id, bank_soal_id, kode_ujian, waktu_mulai, waktu_selesai, status
- `JawabanSiswa`: Student answers - ujian_id, soal_id, jawaban_siswa, is_benar
- `HasilUjian`: Exam results - ujian_id, nilai, waktu_penyelesaian
- `BankSoal`: Exam bank - guru_id, mata_pelajaran_id, durasi, jadwal_mulai, jadwal_selesai
- `PenugasanGuru`: Teacher assignments - guru_id, mata_pelajaran_id, kelas_id

## Implementation Tasks

### Phase 6A: Dashboard & Exam Entry
- [ ] Create SiswaController for dashboard
- [ ] Create views:
  - [ ] Siswa dashboard
  - [ ] Exam code entry form
- [ ] Validation logic for exam code (timing, format, permissions)
- [ ] Redirect logic post-validation

### Phase 6B: Exam Interface
- [ ] Create ExamController for exam taking
- [ ] Implement question randomization logic
- [ ] Create exam view with:
  - [ ] Question display
  - [ ] Multiple choice options
  - [ ] Timer/countdown
  - [ ] Navigation buttons
- [ ] Implement auto-save functionality (AJAX)
- [ ] Add security features (tab detection, back button prevention)

### Phase 6C: Answer Submission & Grading
- [ ] Create answer submission logic
- [ ] Implement auto-grading algorithm
- [ ] Store results in hasil_ujian
- [ ] Create result view with score and feedback

### Phase 6D: Security & Optimization
- [ ] Session security checks
- [ ] Prevent multiple simultaneous exams
- [ ] Exam code regeneration every 5 minutes (if needed)
- [ ] Rate limiting on answer submissions

## Routes to Implement

```
GET    /siswa/dashboard                          - Show available exams
POST   /siswa/exam-entry                         - Validate and enter exam
GET    /siswa/exam/{ujian}                       - Display exam interface
POST   /siswa/exam/{ujian}/answer                - Save individual answer
POST   /siswa/exam/{ujian}/submit                - Submit exam for grading
GET    /siswa/hasil/{ujian}                      - Show exam results
```

## Security Considerations

1. **Kode Ujian Validation**:
   - Must match format (XXX-XXX format as uppercase letters/numbers)
   - Regenerate every 5 minutes (optional, can be static)
   - One-time use per student (prevent sharing)

2. **Session Security**:
   - Validate user is in correct class/subject
   - Check exam hasn't started before jadwal_mulai
   - Check exam hasn't ended after jadwal_selesai
   - Lock exam after submission

3. **Answer Security**:
   - Validate jawaban_siswa is one of valid options (a/b/c/d)
   - Prevent answer resubmission after exam closes
   - Verify soal_id belongs to exam's bank_soal

4. **Client-Side Protections**:
   - JavaScript to detect tab/window changes
   - Prevent right-click context menu
   - Prevent F12/DevTools access
   - Override browser back button

## Testing Checklist

- [ ] Test exam code validation with valid/invalid codes
- [ ] Test student can only access own exams
- [ ] Test randomization produces different order per student
- [ ] Test auto-save saves all answers correctly
- [ ] Test auto-grading calculates score accurately
- [ ] Test timer counts down correctly
- [ ] Test tab-switching triggers logout
- [ ] Test prevented access after exam deadline
- [ ] Test results display correctly with score >= 70 as passing

## Performance Considerations

- Cache exam questions to reduce database queries
- Use AJAX for answer auto-save to avoid page reloads
- Limit timer updates to every 1000ms (not too frequent)
- Consider indexing ujian_id and siswa_id in jawaban_siswa
