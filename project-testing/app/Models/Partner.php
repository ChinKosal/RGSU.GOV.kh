<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $table = 'partners';
    protected $fillable = [
        'name',
        'link',
        'logo',
        'status',
        'user_id'
    ];

    protected $appends = ['logo_url'];
    public function getLogoUrlAttribute()
    {
        if ($this->logo) return asset('file_manager' . $this->logo);
        return asset('images/logo/no.jpg');
    }
}
