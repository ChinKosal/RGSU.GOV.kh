<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'role',
        'address',
        'status',
        'remember_token',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'avatar_url',
        'created_date',
    ];

    /**
     * Get the avatar url attribute.
     *
     * @return string
     */

    public function getAvatarProfileAttribute()
    {
        return (new \Laravolt\Avatar\Avatar)->create($this->name)->toBase64();
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset(($this->role == config('dummy.user.role.admin') ? "file_manager" : 'uploads/user') . $this->avatar) : (new \Laravolt\Avatar\Avatar)->create($this->name)->toBase64();
    }

    /**
     * Get the created date attribute.
     *
     * @return string
     */
    public function getCreatedDateAttribute()
    {
        return $this->created_at->format('d/M/Y');
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'company_departments', 'company_id', 'department_id');
    }
    public function ModelHasPermission()
    {
        return $this->hasMany(ModelHasPermission::class, 'model_id');
    }
}
