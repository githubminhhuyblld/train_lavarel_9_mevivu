<?php
namespace App\Enums\Status;
use App\Supports\Enum;

enum Status: int
{
    use Enum;

    case Active = 1;
    case Locked = 2;
}
