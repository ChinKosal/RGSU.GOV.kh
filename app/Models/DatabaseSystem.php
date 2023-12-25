<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatabaseSystem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'database_systems';
    protected $fillable = [
        'title',
        'link',
        'user_id',
        'status',
    ];
}
