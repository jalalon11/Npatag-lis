<?php

namespace App\Providers;

use App\Models\SupportMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share counts with the app layout
        View::composer('layouts.app', function ($view) {
            $pendingSupportCount = 0;
            $adminSupportCount = 0;

            if (Auth::check()) {
                // For admin users
                if (Auth::user()->role === 'admin') {
                    // Count unread support messages for admin
                    $pendingSupportCount = SupportMessage::whereHas('ticket', function($query) {
                        $query->where('status', '!=', 'closed');
                    })
                    ->where('user_id', '!=', Auth::id())
                    ->where('is_read', false)
                    ->count();
                }


            }

            $view->with([
                'pendingSupportCount' => $pendingSupportCount,
                'adminSupportCount' => $adminSupportCount
            ]);
        });
    }
}
