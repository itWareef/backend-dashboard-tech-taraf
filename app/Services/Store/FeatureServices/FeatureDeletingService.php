<?php

namespace App\Services\Store\FeatureServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class FeatureDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف ميزه بنجاح';
    }
}
