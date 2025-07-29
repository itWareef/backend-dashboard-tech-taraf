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
    ];

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
