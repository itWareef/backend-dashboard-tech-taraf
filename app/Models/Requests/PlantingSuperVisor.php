<?php

namespace App\Models\Requests;

use App\Models\SuperVisorVisit;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PlantingSuperVisor extends SuperVisorRequests
{
    protected $table ='spervisor_planting_request';

    protected function additionalAttributes(): array
    {
        return [
            'planting_id'
        ];
    }
    public function maintenance()
    {
        return $this->belongsTo(PlantingRequest::class,'planting_id');
    }
    public function superVisorVisits(): MorphMany
    {
        return $this->morphMany(SuperVisorVisit::class, 'request');
    }
}
