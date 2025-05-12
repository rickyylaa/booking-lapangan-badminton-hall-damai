<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'address',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Inactive</span>';
        }
        return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Active</span>';
    }

    public function getStatusNonLabelAttribute()
    {
        if ($this->status == 0) {
            return 'Inactive';
        }
        return 'Active';
    }
}
