<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Core\Interfaces\HasManyRelations;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;

class GardenRequest extends Model implements HasManyRelations
{
    protected $fillable = [
        'unit_type',
        'customer_id',
        'space',
        'location',
        'visit_type',
        'type',
        'notes',
        'action',
        'latitude',
        'longitude',
        'number',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($gardenRequest) {
            if (empty($gardenRequest->number)) {
                $gardenRequest->number = NumberingService::generateNumber(GardenRequest::class);
            }
        });
    }

    public const TYPES =['gardening','landscape_services','public_health'];
    public const VISIT_TYPES =['once','annually'];
    protected $casts = [
        'visit_type' => 'string',
    ];

    public function attachments()
    {
        return $this->hasMany(GardenRequestAttachment::class,'garden_id');
    }

    public function getHasManyRelations(): array
    {
        return [
            'attachments'
        ];
    }
}
