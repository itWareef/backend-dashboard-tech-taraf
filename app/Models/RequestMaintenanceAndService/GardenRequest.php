<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Core\Interfaces\HasManyRelations;
use App\Models\Invoice;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
    
    // Status constants
    public const STATUSES = ['pending', 'approved', 'in_progress', 'completed', 'rejected'];
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const REJECTED = 'rejected';
    
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

    /**
     * Get the invoice for this garden request.
     */
    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
