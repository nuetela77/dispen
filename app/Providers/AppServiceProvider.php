<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\PengajuanIzin;

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
        // Force HTTPS URL generation when accessed through Cloudflare/SSL proxy tunnel
        if (
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        ) {
            URL::forceScheme('https');
        }

        // Share pending permits count to the layout for real-time notification badge
        View::composer('layouts.app', function ($view) {
            try {
                if (auth()->check() && auth()->user()->isGuruPetugas()) {
                    $pendingCount = PengajuanIzin::whereHas('verifikasiWajah')->where('status', 'menunggu')->count();
                    $view->with('pendingCount', $pendingCount);
                }
            } catch (\Throwable $e) {
                $view->with('pendingCount', 0);
            }
        });
    }
}
