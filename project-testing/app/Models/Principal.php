<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Principal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'principals';
    protected $fillable = [
        'title',
        'slug',
        'type',
        'category',
        'content',
        'file',
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

    protected $appends = ['thumbnail','path'];

    public function getThumbnailAttribute()
    {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);

        if ($this->file) return asset('images/logo/extensions/' . $extension . '.png');
        return 'https://via.placeholder.com/300x300/eff3f5/4871f7?text=' . $extension;
    }

    public function getPathAttribute()
    {
        if ($this->file) return asset('file_manager' . $this->file);
        return null;
    }
}
