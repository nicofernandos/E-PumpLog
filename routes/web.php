<?php

use Illuminate\Support\Facades\Route;
// Admin Controller
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Datmas\LokasiController;
use App\Http\Controllers\Admin\Datmas\PompaController;
use App\Http\Controllers\Admin\Report\ReportController as AdminReportController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Auth\AuthController;

// User Controller 
use App\Http\Controllers\User\DashboardController as UsersDashboardController; 
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\User\DailyReportController as UserDailyReportController;
use App\Http\Controllers\User\ProfileController as UserProfileController;


Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============ ADMIN ROUTES ============
Route::prefix('admin')->as('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::prefix('lokasi')->name('lokasi.')->group(function () {
        Route::get('/', [LokasiController::class, 'index'])->name('index');
        Route::post('/store', [LokasiController::class, 'store'])->name('store');
        Route::put('/{lokasi}', [LokasiController::class, 'update'])->name('update');
        Route::delete('/{lokasi}', [LokasiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pompa')->name('pompa.')->group(function () {
        Route::get('/', [PompaController::class, 'index'])->name('index');
        Route::post('/store', [PompaController::class, 'store'])->name('store');
        Route::put('/{pompa}', [PompaController::class, 'update'])->name('update');
        Route::delete('/{pompa}', [PompaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/approved', [AdminReportController::class,'approved'])->name('approved');
        Route::patch('/{id}/revoke-approval', [AdminReportController::class,'revokeApproval'])->name('revokeApproval');
        Route::get('/revisi',[AdminReportController::class,'revisi'])->name('revisi');
        Route::get('/export/excel', [AdminReportController::class,'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [AdminReportController::class,'exportPdf'])->name('export.pdf');
        Route::get('/', [AdminReportController::class,'index'])->name('index');
        Route::get('/{id}', [AdminReportController::class, 'show'])->name('show');
        Route::patch('/{id}/approve', [AdminReportController::class,'approve'])->name('approve');
        Route::patch('/{id}/rejected', [AdminReportController::class,'reject'])->name('reject');
        Route::get('/{id}/print', [AdminReportController::class,'print'])->name('print');
        Route::delete('/{id}', [AdminReportController::class,'destroy'])->name('destroy');
    });
}); 

// ============ USER ROUTES ============
Route::middleware(['auth'])->as('user.')->group(function () {
    
    Route::get('/dashboard', [UsersDashboardController::class, 'index'])->name('dashboard.index');

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [UserReportController::class, 'index'])->name('index');
        Route::post('/', [UserReportController::class, 'store'])->name('store');
        Route::get('/list', [UserReportController::class, 'list'])->name('list');
    });

    Route::prefix('dailyreport')->name('dailyreport.')->group(function () {
        Route::get('/', [UserDailyReportController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [UserDailyReportController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserDailyReportController::class, 'update'])->name('update');
        Route::patch('/{id}/submit', [UserDailyReportController::class, 'submitDraft'])->name('submit');
        Route::delete('/{id}', [UserDailyReportController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('profile')->name('profile.')->group(function() { 
        Route::get('/',[UserProfileController::class,'index'])->name('index');
        Route::put('/update',[UserProfileController::class,'update'])->name('update');
        Route::put('/password',[UserProfileController::class,'updatePassword'])->name('updatePassword');
    });
});