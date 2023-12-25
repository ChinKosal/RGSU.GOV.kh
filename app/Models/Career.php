<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'careers';
    protected $fillable = [
        'title',
        'slug',
        'start_date',
        'end_date',
        'content',
        'file',
        'status',
        'user_id',
    ];
}
