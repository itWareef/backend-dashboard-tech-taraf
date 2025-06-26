<?php

namespace App\Services\RequestsServices;

use App\Models\Requests\PlantingRequest;

class PlantingRequestStoringService extends AbstractRequestStoring
{

    public function model(): string
    {
        return PlantingRequest::class;
    }

    public function messageSuccessAction(): string
    {
        return "تم إضافة طلب زراعة جديد ";
    }
}
