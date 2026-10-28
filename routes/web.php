<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\Pimpinan\PimpinanDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TpsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // Redirect generik "/dashboard" -> dashboard sesuai role user yang login
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'pimpinan.dashboard');
    })->name('dashboard');

    // Dashboard & kelola pengguna khusus Admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // ==========================================
        // USER MANAGEMENT (CRUD Lengkap)
        // ==========================================
        Route::prefix('users')->name('users.')->group(function () {
            // Halaman daftar user
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            
            // Halaman tambah user
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            
            // Simpan user baru
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            
            // Halaman edit user
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            
            // Update user
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            
            // Hapus user
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
            
            // Update role (admin <-> pimpinan)
            Route::patch('/{user}/role', [UserManagementController::class, 'updateRole'])->name('role');
            
            // Toggle status aktif/nonaktif
            Route::patch('/{user}/toggle', [UserManagementController::class, 'toggleActive'])->name('toggle');
            
            // Reset password user
            Route::patch('/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('reset-password');
        });
        // ==========================================

        // ==========================================
        // DATA TPS (Tambah / Edit / Hapus) - Khusus Admin
        // ==========================================
        Route::prefix('tps')->name('tps.')->group(function () {
            // Halaman tambah data TPS
            Route::get('/create', [TpsController::class, 'create'])->name('create');

            // Simpan data TPS baru
            Route::post('/', [TpsController::class, 'store'])->name('store');

            // Halaman edit data TPS
            Route::get('/{id_tps}/edit', [TpsController::class, 'edit'])->name('edit');

            // Update data TPS
            Route::put('/{id_tps}', [TpsController::class, 'update'])->name('update');

            // Hapus data TPS
            Route::delete('/{id_tps}', [TpsController::class, 'destroy'])->name('destroy');

            // Export data TPS ke Excel (mengikuti filter yang aktif)
            Route::get('/export', [TpsController::class, 'export'])->name('export');

            // Unduh template Excel untuk import
            Route::get('/template', [TpsController::class, 'downloadTemplate'])->name('template');

            // Import data TPS dari Excel
            Route::post('/import', [TpsController::class, 'import'])->name('import');
        });
        // ==========================================
    });
    Route::post('/tps/import', [TpsController::class, 'import'])
    ->name('tps.import');

    // Dashboard khusus Pimpinan
    Route::prefix('pimpinan')->name('pimpinan.')->middleware('role:pimpinan')->group(function () {
        Route::get('/dashboard', [PimpinanDashboardController::class, 'index'])->name('dashboard');
    });

    // Data TPS & Klaster — bisa diakses semua role yang sudah login
    Route::get('/tps', [TpsController::class, 'index'])->name('tps.index');
    Route::get('/tps/{id_tps}', [TpsController::class, 'show'])->name('tps.show');

    Route::get('/klaster', [ClusterController::class, 'index'])->name('clusters.index');
    Route::get('/klaster/{cluster_no}', [ClusterController::class, 'show'])->name('clusters.show');

    // Profil akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';