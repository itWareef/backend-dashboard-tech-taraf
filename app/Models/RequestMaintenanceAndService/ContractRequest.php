<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;

class ContractRequest extends Model
{
    protected $fillable = [
        'number',
        'customer_id',
        'ownership_number',
        'developer',
        'project',
        'property_type',
        'property_age',
        'area',
        'location',
        'payment_status',
        'request_status',
        'contract_type',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($contractRequest) {
            if (empty($contractRequest->number)) {
                $contractRequest->number = NumberingService::generateNumber(ContractRequest::class);
            }
        });
    }

    protected $casts=[
        'contract_type' => 'array'
    ];
}
