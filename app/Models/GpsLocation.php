<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsLocation extends Model
{
    protected $fillable = ['supervisor_id', 'latitude', 'longitude', 'recorded_at'];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class,'supervisor_id');
    }
}
