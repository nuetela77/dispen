<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruPetugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru_petugas' => redirect()->route('guru.dashboard'),
            default => redirect()->route('siswa.dashboard'),
        };
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/verifikasi/{kode}', [GuruPetugasController::class, 'verifikasi'])->name('verifikasi.surat')->where('kode', '[A-Z0-9]{8}');

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/ajukan', [SiswaController::class, 'formPengajuan'])->name('form');
    Route::post('/ajukan', [SiswaController::class, 'simpanPengajuan'])->name('simpan');
    Route::get('/verifikasi-wajah/{pengajuan}', [SiswaController::class, 'showVerifikasiWajah'])->name('verifikasi-wajah');
    Route::post('/verifikasi-wajah/{pengajuan}', [SiswaController::class, 'simpanVerifikasiWajah'])->name('simpan-verifikasi');
    Route::get('/detail/{pengajuan}', [SiswaController::class, 'detail'])->name('detail');
    Route::get('/download/{pengajuan}', [SiswaController::class, 'downloadSurat'])->name('download');
});

Route::middleware(['auth', 'role:guru_petugas'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruPetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/detail/{pengajuan}', [GuruPetugasController::class, 'detail'])->name('detail');
    Route::post('/approve/{pengajuan}', [GuruPetugasController::class, 'approve'])->name('approve');
    Route::post('/reject/{pengajuan}', [GuruPetugasController::class, 'reject'])->name('reject');
    Route::get('/surat/view/{suratIzin}', [GuruPetugasController::class, 'viewPDF'])->name('view-pdf');
    Route::get('/galeri-foto', [GuruPetugasController::class, 'galeriFoto'])->name('galeri-foto');
    Route::get('/kelola-siswa', [GuruPetugasController::class, 'kelolaSiswa'])->name('kelola-siswa');
    Route::get('/riwayat', [GuruPetugasController::class, 'riwayat'])->name('riwayat');
    Route::get('/export-csv', [GuruPetugasController::class, 'exportCSV'])->name('export-csv');
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::post('/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
});

// Route untuk menyajikan file storage (foto wajah & PDF) secara langsung di semua environment
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');

// Halaman diagnostik dan uji coba pengiriman Bot WhatsApp Fonnte
Route::match(['get', 'post'], '/test-wa', function (\Illuminate\Http\Request $request) {
    $result = null;
    $target = $request->input('target', env('GURU_PIKET_WA', config('services.fonnte.guru_wa', '')));
    $token = $request->input('token', env('FONNTE_TOKEN', config('services.fonnte.token', '')));

    if ($request->isMethod('post')) {
        $result = \App\Services\WhatsAppService::testKirim($target, $token);
    }

    return view('test-wa', compact('result', 'target', 'token'));
})->name('test-wa');
