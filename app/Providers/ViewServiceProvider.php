<?php

namespace App\Providers;

use App\Manager\Menu\MenuManager;
use App\Manager\MenuItem\MenuItemManager;
use App\Models\Menu\Menu;
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
            $menuItemManager = new MenuItemManager();
            $menuItems = $menuItemManager->getOrderBy();
            $menu = Menu::first();
            $backgroundColor = $menu ? $menu->background : '#ccc';
            $menuColor = $menu ? $menu->menu_color : '#ccc';
            $menuFont = $menu ? $menu->menu_font : '16';
            $view->with([
                'menuItems' => $menuItems,
                'backgroundColor' => $backgroundColor,
                'menuFont' => $menuFont,
                'menuColor' => $menuColor
            ]);
        });
    }
}
