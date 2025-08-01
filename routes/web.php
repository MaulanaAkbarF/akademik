<?php
// routes/web.php - Updated with SKS features

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SksTransactionController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Role-based dashboard routes with middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // SKS Transaction Management
    Route::get('/admin/sks-transactions', [SksTransactionController::class, 'indexAdmin'])->name('admin.sks.index');
    Route::patch('/admin/sks-transactions/{sksTransaction}/status', [SksTransactionController::class, 'updateStatus'])->name('admin.sks.update-status');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
    Route::get('/dosen/jadwal', [DosenController::class, 'jadwal'])->name('dosen.jadwal');
    Route::get('/dosen/presensi', [PresensiController::class, 'indexDosen'])->name('dosen.presensi.index');
    Route::get('/dosen/presensi/{kodeMk}', [PresensiController::class, 'showDosen'])->name('dosen.presensi.show');
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/jadwal', [MahasiswaController::class, 'jadwal'])->name('mahasiswa.jadwal');
    Route::get('/mahasiswa/presensi', [PresensiController::class, 'indexMahasiswa'])->name('mahasiswa.presensi.index');
    Route::post('/mahasiswa/presensi', [PresensiController::class, 'storeMahasiswa'])->name('mahasiswa.presensi.store');
    
    // SKS Transaction Routes
    Route::get('/mahasiswa/sks', [SksTransactionController::class, 'index'])->name('mahasiswa.sks.index');
    Route::get('/mahasiswa/sks/create', [SksTransactionController::class, 'create'])->name('mahasiswa.sks.create');
    Route::post('/mahasiswa/sks', [SksTransactionController::class, 'store'])->name('mahasiswa.sks.store');
    
    // KRS (Course Registration) Routes
    Route::get('/mahasiswa/krs', [SksTransactionController::class, 'takeMatakuliah'])->name('mahasiswa.krs.index');
    Route::post('/mahasiswa/krs', [SksTransactionController::class, 'storeMatakuliah'])->name('mahasiswa.krs.store');
    Route::delete('/mahasiswa/krs', [SksTransactionController::class, 'dropMatakuliah'])->name('mahasiswa.krs.drop');
});

// Fallback home route
Route::get('/home', function () {
    $user = Auth::user();
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'dosen':
            return redirect()->route('dosen.dashboard');
        case 'mahasiswa':
            return redirect()->route('mahasiswa.dashboard');
        default:
            return view('home');
    }
})->middleware('auth')->name('home');