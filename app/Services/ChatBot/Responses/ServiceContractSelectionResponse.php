<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class ServiceContractSelectionResponse implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), ['التعاقدات', 'Contracts', ]);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Choose service type:"
                : "اختر نوع الخدمة: ",
            'choose' =>  $lang === 'en' ?
                [
                    'Individuals',
                    'Developers'
                ]
                :[
                    'المبيعات',
                    'خدمات المطورين'
                ]
        ];
    }
}
