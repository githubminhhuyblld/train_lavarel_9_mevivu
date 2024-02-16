<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'status'
    ];

    public function menu():BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

}
