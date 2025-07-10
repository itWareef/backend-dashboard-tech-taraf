<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class ServiceContractServiceSelectionResponse implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), ['Developers', 'Individuals', 'المبيعات', 'خدمات المطورين']);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Choose service type:"
                : "اختر نوع الخدمة: ",
            'choose' =>  $lang === 'en' ?
                [
                    'Real Estate Marketing',
                    'Property Management',
                    'Facilities Management,',
                    'Landscape,',
                    'Event Planning'
                ]
                :[
                    'التسويق العقاري',
                    'لإدارة الأملاك',
                    'لإدارة المرافق',
                    'للاند سكيب',
                    'لتنظيم الفعالبات'
                ]
        ];
    }
}
