<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('guru', \App\Http\Controllers\Admin\GuruController::class);
    Route::resource('siswa', \App\Http\Controllers\Admin\SiswaController::class);
    Route::resource('mata-pelajaran', \App\Http\Controllers\Admin\MataPelajaranController::class);
    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class);
    Route::resource('jurusan', \App\Http\Controllers\Admin\JurusanController::class);
    Route::resource('penugasan-guru', \App\Http\Controllers\Admin\PenugasanGuruController::class);
});

// Guru routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Guru\DashboardController::class . '@index')->name('dashboard');
    
    Route::resource('bank-soal', \App\Http\Controllers\Guru\BankSoalController::class);
    
    Route::prefix('bank-soal/{bankSoal}/soal')->name('soal.')->group(function () {
        Route::get('/create', \App\Http\Controllers\Guru\SoalController::class . '@create')->name('create');
        Route::post('/', \App\Http\Controllers\Guru\SoalController::class . '@store')->name('store');
        Route::get('/{soal}/edit', \App\Http\Controllers\Guru\SoalController::class . '@edit')->name('edit');
        Route::patch('/{soal}', \App\Http\Controllers\Guru\SoalController::class . '@update')->name('update');
        Route::delete('/{soal}', \App\Http\Controllers\Guru\SoalController::class . '@destroy')->name('destroy');
    });
    
    Route::get('/hasil-ujian', \App\Http\Controllers\Guru\HasilUjianController::class . '@index')->name('hasil-ujian.index');
    Route::get('/hasil-ujian/{ujian}', \App\Http\Controllers\Guru\HasilUjianController::class . '@show')->name('hasil-ujian.show');
    Route::get('/bank-soal/{bankSoal}/hasil', \App\Http\Controllers\Guru\HasilUjianController::class . '@byBankSoal')->name('hasil-by-bank-soal');
    Route::get('/bank-soal/{bankSoal}/statistik', \App\Http\Controllers\Guru\HasilUjianController::class . '@statistics')->name('bank-soal.statistik');
});

// Siswa routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('siswa.ujian.index');
    })->name('dashboard');

    Route::resource('ujian', \App\Http\Controllers\Siswa\UjianController::class)
        ->only(['index', 'show', 'store']);

    Route::post('/ujian/{ujian}/answer', [\App\Http\Controllers\Siswa\UjianController::class, 'saveAnswer'])
        ->name('ujian.answer');

    Route::post('/ujian/{ujian}/violation', [\App\Http\Controllers\Siswa\UjianController::class, 'recordViolation'])
        ->name('ujian.violation');

    Route::post('/ujian/{ujian}/verify-code', [\App\Http\Controllers\Siswa\UjianController::class, 'verifyCode'])
        ->name('ujian.verify-code');

    Route::post('/ujian/{ujian}/submit', [\App\Http\Controllers\Siswa\UjianController::class, 'submit'])
        ->name('ujian.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
