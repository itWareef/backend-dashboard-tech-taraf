<?php

namespace App\Services\RequestsServices;

use App\Models\Requests\MaintenanceRequest;

class MaintenanceRequestStoringService extends AbstractRequestStoring
{

    public function model(): string
    {
        return MaintenanceRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم إضافة طلب صيانة جديد ";
    }
}
