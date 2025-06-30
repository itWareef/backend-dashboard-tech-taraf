<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const TYPES =['maintenance','planting'];
    protected $fillable =[
        'name',
        'type'
    ];
    public const MAINTENANCE = 'maintenance';
    public const PLANTING = 'planting';
}
