<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class ServiceAfterSalesSelectionResponse implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), ['خدمات مابعد البيع', 'After-sales services', ]);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Choose service type:"
                : "اختر نوع الخدمة: ",
            'choose' =>  $lang === 'en' ?
                [
                    'Facility Management',
                    'Landscape',
                    'Maintenance and Operation',
                    'Other Request'
                ]
                :[
                    'إدارة المرافق',
                    'الصيانة والتشغيل',
                    'اللاند سكيب',
                    'طلب أخر'
                ]
        ];
    }
}
