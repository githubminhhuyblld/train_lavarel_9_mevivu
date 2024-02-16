<?php

namespace App\Http\Controllers\Menu;

use App\Http\Requests\MenuItem\MenuItemRequest;
use App\Manager\MenuItem\MenuItemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Manager\Menu\MenuManager;
use Illuminate\View\View;

class MenuController extends Controller
{
    protected $menuManager;
    protected $menuItemManager;

    public function __construct(MenuManager $menuManager, MenuItemManager $menuItemManager)
    {
        $this->menuManager = $menuManager;
        $this->menuItemManager = $menuItemManager;
    }

    public function index(): View
    {
        $menuItems = $this->menuItemManager->getOrderBy();
        $menu = $this->menuManager->findById(1);

        return view('user.menu.index', compact('menuItems', 'menu'));
    }

    public function create(): View
    {

        return view('user.menuItem.create');
    }

    public function updateMenu(Request $request): JsonResponse
    {
        $menu = $request->input('menu');
        $backgroundColor = $request->input('background');
        $menuFont = $request->input('menu_font');
        $menuColor = $request->input('menu_color');
        $this->menuManager->updateAttribute(1, 'background', $backgroundColor);
        $this->menuManager->updateAttribute(1,'menu_font',$menuFont );
        $this->menuManager->updateAttribute(1,'menu_color',$menuColor );
        $menuItemIds = array_column($menu, 'id');
        foreach ($menu as $menuData) {
            $menuItem = $this->menuItemManager->findById($menuData['id']);
            if ($menuItem) {
                $this->menuItemManager->updateAttribute($menuItem->id, 'order', $menuData['position']);
                $this->menuItemManager->updateAttribute($menuItem->id, 'status', 1);
            }
        }
        $allMenuItems = $this->menuItemManager->getAll();
        foreach ($allMenuItems as $menuItem) {
            if (!in_array($menuItem->id, $menuItemIds)) {
                $this->menuItemManager->updateAttribute($menuItem->id, 'status', 2);

            }
        }

        return response()->json(['success' => true, 'message' => 'Menu order updated successfully.']);
    }


    public function storeMenuItem(MenuItemRequest $request): JsonResponse
    {
        $menu = $this->menuManager->getAll()->first();
        if ($menu) {
            $menuId = $menu->id;
            $menuItem = [
                'menu_id' => $menuId,
                'title' => $request['title'],
                'slug' => $request['slug'],
            ];
            $this->menuItemManager->create($menuItem);
            return response()->json(['success' => 'register successfully!']);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found.'
            ], 404);
        }

    }


}
