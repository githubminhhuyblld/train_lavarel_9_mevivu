<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends  Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'menu_font',
        'menu_color',
        'background',
        'status',
    ];
    public function menuItems():HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
