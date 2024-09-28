<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
class MenuComposerServiceProvide extends ServiceProvider
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
        //
        view()->composer('*', function ($view) {
            $mainMenus = Menu::whereNull('parent_id')->orderBy('order', 'asc')->get();
            $view->with('mainMenus', $mainMenus);
        });
    }
}
