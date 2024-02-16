<?php

namespace App\Manager\MenuItem;

use App\Constants\Entity\BaseEntityManager;
use App\Models\Menu\Menu;
use App\Models\Menu\MenuItem;

class MenuItemManager
{
    use BaseEntityManager;
    protected function getModelClass(): string
    {
        return MenuItem::class;
    }





}
