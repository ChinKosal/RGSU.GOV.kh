<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menus';
    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'path',
        'active',
        'disabled_at',
        'ordering',
        'permission',
    ];
    protected $casts = [
        'permission' => 'array',
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
