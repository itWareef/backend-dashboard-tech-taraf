<?php

namespace App\Models\Customer;

use App\Models\HandleToArrayTrait;
use App\Models\Project\Project;
use App\Models\Project\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
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
        'phone'
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
    public function projects()
    {
        return $this->hasManyThrough(
            Project::class,     // الجدول النهائي اللي عايزه
            Unit::class,        // الجدول الوسيط
            'owner_id',         // المفتاح الأجنبي في جدول units الذي يشير إلى customers.id
            'id',               // المفتاح الأساسي في جدول projects الذي units.project_id بيشاور عليه
            'id',               // المفتاح الأساسي في جدول customers
            'project_id'        // المفتاح الأجنبي في جدول units الذي يشير إلى projects.id
        );
    }

}
