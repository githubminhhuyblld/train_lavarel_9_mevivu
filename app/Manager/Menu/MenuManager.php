<?php

namespace App\Manager\Menu;

use App\Constants\Entity\BaseEntityManager;
use App\Models\Menu\Menu;

class MenuManager
{
    use BaseEntityManager;
    protected function getModelClass(): string
    {
        return Menu::class;
    }





}
