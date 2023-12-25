<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'social_media';
    protected $fillable = [
        'name',
        'link',
        'icon',
        'type',
        'status',
        'user_id',
    ];

    protected $appends = ['icon_url'];
    public function getIconUrlAttribute()
    {
        if ($this->icon) return asset('file_manager' . $this->icon);
        return asset('images/logo/no.jpg');
    }
}
