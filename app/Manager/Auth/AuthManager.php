<?php

namespace App\Manager\Auth;

use App\Constants\Entity\BaseEntityManager;
use App\Models\User;

class AuthManager
{
    use BaseEntityManager;
    protected function getModelClass(): string
    {
        return User::class;
    }

}
