<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory;
    protected $fillable=[
        'unit_id',
        'customer_id',
        'start_date',
        'end_date',
        'type'
    ];
    public const TYPES =['maintenance','planting'];
}
