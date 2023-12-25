<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;
    protected $table = 'user_devices';
    protected $fillable = [
        'device_id',
        'device_name',
        'model',
        'os',
    ];
}
