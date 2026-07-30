<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.landing', function ($view) {
            $view->with('menus', \App\Models\Menu::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('order')
                ->with(['children' => function($q) {
                    $q->where('is_active', true)->orderBy('order');
                }])
                ->get());
        });
    }
}
