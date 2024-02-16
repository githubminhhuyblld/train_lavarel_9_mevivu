<?php

namespace App\Http\Controllers\MenuItem;


use App\Manager\MenuItem\MenuItemManager;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    protected $menuItemsManager;

    public function __construct(MenuItemManager $menuItemsManager)
    {
        $this->menuItemsManager = $menuItemsManager;
    }






}
