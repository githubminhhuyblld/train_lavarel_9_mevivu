<?php

namespace App\Providers;

use App\Models\Menu\MenuItem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer('layouts.header.header', function ($view) {
            $view->with('menuItems', MenuItem::where('status', 1)->get());
        });
    }
}
