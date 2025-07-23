<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Core\Interfaces\HasManyRelations;
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
    ];

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
