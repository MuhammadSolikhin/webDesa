<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\User\TransactionController as UserTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    
    // User Dashboard (Accessible by all verified users)
    Route::get('/user/dashboard', function () {
        $transactions = \App\Models\Transaction::with('tourPackage')->where('user_id', auth()->id())->latest()->get();
        return view('user.dashboard', compact('transactions'));
    })->name('user.dashboard');
    
    Route::get('/user/transactions', [UserTransactionController::class, 'index'])->name('user.transactions.index');
    Route::get('/user/active-packages', [UserTransactionController::class, 'active'])->name('user.transactions.active');

    // Checkout Routes
    Route::get('/checkout/{package}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{package}', [CheckoutController::class, 'process'])->name('checkout.process');

    // Admin Dashboard and Admin Routes (Only accessible by admin)
    Route::middleware(['is_admin'])->group(function () {
        Route::get('/dashboard', function () {
            $data = [
                'menusCount' => \App\Models\Menu::count(),
                'servicesCount' => \App\Models\Service::count(),
                'portfoliosCount' => \App\Models\Portfolio::count(),
                'tourPackagesCount' => \App\Models\TourPackage::count(),
                'heroesCount' => \App\Models\Hero::count(),
            ];
            return view('dashboard', $data);
        })->name('dashboard');

        Route::get('/admin/landing', [\App\Http\Controllers\Admin\LandingPageController::class, 'edit'])->name('admin.landing.edit');
        Route::post('/admin/landing', [\App\Http\Controllers\Admin\LandingPageController::class, 'update'])->name('admin.landing.update');
        
        Route::resource('/admin/tour-package', \App\Http\Controllers\Admin\TourPackageController::class)->names('admin.tour-package');
        Route::resource('/admin/menu', \App\Http\Controllers\Admin\MenuController::class)->names('admin.menu');
        Route::post('/admin/menu/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('admin.menu.reorder');
        Route::resource('/admin/hero', \App\Http\Controllers\Admin\HeroController::class)->names('admin.hero');
        Route::resource('/admin/service', \App\Http\Controllers\Admin\ServiceController::class)->names('admin.service');
        Route::resource('/admin/portfolio', \App\Http\Controllers\Admin\PortfolioController::class)->names('admin.portfolio');
        
        Route::get('/admin/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions.index');
        Route::get('/admin/active-packages', [AdminTransactionController::class, 'active'])->name('admin.transactions.active');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Midtrans Webhook
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'callback']);
