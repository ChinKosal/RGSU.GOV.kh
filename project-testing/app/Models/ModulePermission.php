<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    use HasFactory;
    protected $table = 'module_permissions';
    public function permission()
    {
        return $this->hasMany(Permission::class, 'module_id');
    }

}
