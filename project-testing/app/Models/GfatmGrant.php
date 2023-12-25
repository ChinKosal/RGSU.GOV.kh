<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GfatmGrant extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'gfatm_grants';
    protected $fillable = [
        'title',
        'slug',
        'category',
        'link',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return Category::whereIn('id', json_decode($this->category))->orderBy('ordering')->get();
    }
}
