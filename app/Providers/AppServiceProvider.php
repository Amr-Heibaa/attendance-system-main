<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $unreadNotificationsCount = Notification::where('is_read', false)->count();

            $latestNavbarNotifications = Notification::latest()
                ->take(5)
                ->get();

            $view->with([
                'unreadNotificationsCount' => $unreadNotificationsCount,
                'latestNavbarNotifications' => $latestNavbarNotifications,
            ]);
        });
    }
}