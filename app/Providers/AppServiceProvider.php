<?php

namespace App\Providers;

use App\Models\Pengajuan;
use App\Models\User;
use App\Observers\PengajuanObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        if (isset($_SERVER['HTTP_X_ORIGINAL_HOST']) && ! empty($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
            $url = 'https://'.$_SERVER['HTTP_X_ORIGINAL_HOST'];
            \URL::forceRootUrl($url);
            \URL::forceScheme('https');
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \URL::forceScheme('https');
        }

        Paginator::defaultView('vendor.pagination.custom');
        // This composer provides fresh mahasiswa data to all views in the 'mahasiswa'
        // directory and its subdirectories. This ensures the layout always has the latest data.
        View::composer('mahasiswa.*', function ($view) {
            if (Auth::check()) {
                // Eager load the 'mahasiswa' relationship to get the latest profile data.
                $user = User::with('mahasiswa')->find(Auth::id());

                // Share the fresh mahasiswa object with the views.
                // Using a specific variable name to avoid conflicts with controllers.
                $view->with('mahasiswa_for_layout', $user ? $user->mahasiswa : null);
            }
        });

        Pengajuan::observe(PengajuanObserver::class);
    }
}
