<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/proyek', [PageController::class, 'projects'])->name('projects');
Route::get('/proyek/{ref}', [PageController::class, 'project'])->name('project');
Route::get('/fotografi', [PageController::class, 'photography'])->name('photography');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [PageController::class, 'send'])->name('contact.send');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.projects.index'))->name('dashboard');

        Route::get('proyek', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('proyek/baru', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('proyek', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('proyek/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('proyek/{id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('proyek/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        Route::get('foto', [PhotoController::class, 'index'])->name('photos.index');
        Route::get('foto/baru', [PhotoController::class, 'create'])->name('photos.create');
        Route::post('foto', [PhotoController::class, 'store'])->name('photos.store');
        Route::get('foto/{id}/edit', [PhotoController::class, 'edit'])->name('photos.edit');
        Route::put('foto/{id}', [PhotoController::class, 'update'])->name('photos.update');
        Route::delete('foto/{id}', [PhotoController::class, 'destroy'])->name('photos.destroy');

        // Pesanan & Nota
        Route::get('pesanan', [OrderController::class, 'index'])->name('orders.index');
        Route::get('pesanan/baru', [OrderController::class, 'create'])->name('orders.create');
        Route::post('pesanan', [OrderController::class, 'store'])->name('orders.store');
        Route::get('pesanan/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('pesanan/{id}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('pesanan/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('pesanan/convert/{messageId}', [OrderController::class, 'convertFromMessage'])->name('orders.convert');
        Route::get('pesanan/{id}/nota', [OrderController::class, 'invoice'])->name('orders.invoice');
    });
});
