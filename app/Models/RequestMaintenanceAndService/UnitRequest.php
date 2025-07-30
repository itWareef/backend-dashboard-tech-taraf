<?php

namespace App\Models\RequestMaintenanceAndService;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class UnitRequest extends Model
{
    protected $fillable = [
        'project_name',
        'developer_name',
        'customer_id',
        'unit_no',
        'space',
        'deed_number',
        'date',
        'status',
    ];

    // Status constants
    public const STATUSES = ['pending', 'under_review', 'approved', 'rejected', 'completed'];
    public const PENDING = 'pending';
    public const UNDER_REVIEW = 'under_review';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const COMPLETED = 'completed';

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the invoice for this unit request.
     */
    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
