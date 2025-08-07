<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Category;
use App\Models\ContactMessage;
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
        view()->composer('*', function ($view) {
            $navbarCategories = Category::where('category_status', 'Active')
                                        ->latest()
                                        ->take(6)
                                        ->get();
            $view->with('navbarCategories', $navbarCategories);
        });
        
        View::composer('*', function ($view) {
            if (request()->is('admin/contact*')) {
                $view->with('newMessageCount', 0);
            } else {
                $newMessageCount = ContactMessage::where('is_read', false)->count();
                $view->with('newMessageCount', $newMessageCount);
            }
        });

        View::composer('*', function ($view) {
            $theme = 'light';
        
            if (Auth::check()) {
                $theme = Auth::user()->theme_mode ?? 'light';
            } else {
                $theme = \App\Models\Setting::first()->theme_mode ?? 'light';
            }
        
            $view->with('theme_mode', $theme);
        });

        $setting = Setting::first();

        if ($setting) {
            View::share('siteSetting', $setting);
            View::share('theme_mode', $setting->theme_mode ?? 'light');
        } else {
            View::share('siteSetting', null);
            View::share('theme_mode', 'light');
        }
    }
}
