<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/landing', [\App\Http\Controllers\Admin\LandingPageController::class, 'edit'])->name('admin.landing.edit');
    Route::post('/admin/landing', [\App\Http\Controllers\Admin\LandingPageController::class, 'update'])->name('admin.landing.update');
    
    Route::resource('/admin/menu', \App\Http\Controllers\Admin\MenuController::class)->names('admin.menu');
    Route::post('/admin/menu/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('admin.menu.reorder');
    Route::resource('/admin/hero', \App\Http\Controllers\Admin\HeroController::class)->names('admin.hero');
    Route::resource('/admin/service', \App\Http\Controllers\Admin\ServiceController::class)->names('admin.service');
    Route::resource('/admin/portfolio', \App\Http\Controllers\Admin\PortfolioController::class)->names('admin.portfolio');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
