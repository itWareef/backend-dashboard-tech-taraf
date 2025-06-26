<?php

namespace App\Models\Requests;

class MaintenanceSuperVisor extends SuperVisorRequests
{

    protected function additionalAttributes(): array
    {
        return [
            'maintenance_id'
        ];
    }
    public function maintenance()
    {
        return $this->belongsTo(MaintenanceRequest::class,'maintenance_id');
    }
}
