<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categories';
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'type',
        'ordering',
        'status',
        'user_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function customChildren()
    {
        return $this->hasMany(Category::class, 'parent_id')->whereStatus(config('dummy.status.active.key'))->orderByDesc('id');
    }

    // query news by category with status active and order by id desc and limit 5
    public function customNews()
    {
        return News::query()
                    ->whereJsonContains('category_ids', $this->id)
                    ->whereStatus(config('dummy.status.active.key'))
                    ->orderByDesc('id')
                    ->limit(3)
                    ->get();
    }
}
