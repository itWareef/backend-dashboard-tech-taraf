<?php

namespace App\Models\Project;

use App\Models\Customer\Customer;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
    use HasFactory;
    protected $fillable=[
        'owner_id',
        'project_id',
        'villa_number',
        'deed_number',
        'purchase_date',
        'space',
         'location',
        'number'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($unit) {
            if (empty($unit->number)) {
                $unit->number = NumberingService::generateNumber(Unit::class);
            }
        });
    }

    public function contract(){
        return $this->hasOne(Contract::class,'unit_id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
    public function owner(){
        return $this->belongsTo(Customer::class,'owner_id');
    }
}
