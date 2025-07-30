<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Models\Invoice;
use App\Services\NumberingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
        'status',
    ];

    // Status constants
    public const STATUSES = ['pending', 'under_review', 'approved', 'rejected', 'completed'];
    public const PENDING = 'pending';
    public const UNDER_REVIEW = 'under_review';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const COMPLETED = 'completed';

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

    /**
     * Get the invoice for this contract request.
     */
    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
