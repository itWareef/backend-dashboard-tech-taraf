<?php

namespace App\Models\Customer;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use App\Models\Project\Project;
use App\Models\Project\Unit;
use App\Models\Store\Brand;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable implements FileUpload
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
        'phone',
        'number'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($customer) {
            if (empty($customer->number)) {
                $customer->number = NumberingService::generateNumber(Customer::class);
            }
        });
    }

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

    public function documentFullPathStore(): string
    {
       return 'customers/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'picture'
        ];
    }
    public function favouriteBrands()
    {
        return $this->belongsToMany(Brand::class, 'brand_favourites')
            ->withTimestamps();
    }

}
