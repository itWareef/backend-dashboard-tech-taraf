<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class LanguageSelectionResponse implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), ['العربي', 'arabic', 'الإنجليزي', 'english']);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Choose service type:"
                : "اختر نوع الخدمة: ",
            'choose' =>  $lang === 'en' ?
                [
                    'Sales',
                    'After-sales services',
                    'Contracts'
                ]
                :[
                'المبيعات',
                'خدمات مابعد البيع',
                'التعاقدات'
            ]
        ];
    }
}
