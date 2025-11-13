<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Datmas\LokasiController;
use App\Http\Controllers\Admin\Datmas\PompaController;
use App\Http\Controllers\Admin\Users\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',function(){
    return view('auth.login');
});

Route::prefix('admin')->as('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


    // //Lokasi
    // Route::get('/lokasi',[LokasiController::class,'index'])->name('lokasi');
    // Route::post('/lokasi/store',[LokasiController::class,'store'])->name('lokasi.store');
    // Route::put('/lokasi/{lokasi}',[LokasiController::class,'update'])->name('lokasi.update');
    // Route::delete('/lokasi/{lokasi}',[LokasiController::class,'destroy'])->name('lokasi.destroy');

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

}); 