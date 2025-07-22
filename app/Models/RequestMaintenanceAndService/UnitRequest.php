<?php

namespace App\Models\RequestMaintenanceAndService;

use Illuminate\Database\Eloquent\Model;

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
}
