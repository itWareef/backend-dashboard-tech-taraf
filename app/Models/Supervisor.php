<?php

namespace App\Models;

use App\Models\Project\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Supervisor extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory , Notifiable,HasApiTokens ,HandleToArrayTrait;
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'picture',
        'type',
        'phone'
    ];
    public const TYPES = [
        'planting',
        'maintenance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function units()
    {
        return $this->hasMany(Unit::class,'owner_id');
    }
}
