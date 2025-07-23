<?php

namespace App\Models\RequestMaintenanceAndService;

use Illuminate\Database\Eloquent\Model;

class ContractRequest extends Model
{
    protected $fillable = [
        'request_number',
        'customer_id',
        'ownership_number',
        'developer',
        'project',
        'property_type',
        'property_age',
        'area',
        'latitude',
        'longitude',
        'payment_status',
        'request_status',
        'contract_type',
    ];

    protected $casts=[
        'contract_type' => 'array'
    ];
}
