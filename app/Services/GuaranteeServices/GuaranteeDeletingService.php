<?php

namespace App\Services\GuaranteeServices;

use App\Core\Classes\DeletingData\AbstractClassHandleDelete;


class GuaranteeDeletingService extends AbstractClassHandleDelete
{

    public function messageSuccessAction(): string
    {
        return 'تم حذف ضمان بنجاح';
    }
}
