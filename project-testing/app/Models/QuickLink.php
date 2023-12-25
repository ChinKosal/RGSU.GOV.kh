<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuickLink extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'quick_links';
    protected $fillable = [
        'name',
        'link',
        'image',
        'status',
        'type',
        'user_id',
    ];

    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        if ($this->image) return asset('file_manager' . $this->image);
        return asset('images/logo/no.jpg');
    }
}
