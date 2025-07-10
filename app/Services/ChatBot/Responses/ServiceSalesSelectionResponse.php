<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class ServiceSalesSelectionResponse implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), ['المبيعات', 'Sales', ]);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Choose from the following list of projects:"
                : "اختر من قائمة  المشاريع  التالية : ",
            'choose' =>  $lang === 'en' ?
                [
                    'Wreef Apartment 40',
                    'Aseel-1',
                    'Abadie-1',
                    'Kada-1',
                    'Taghmeer-10'
                ]
                :[
                    'وريف أبارتمنت 40',
                    'أصيل -1',
                    'أبادي -1',
                    'كدا -1',
                    'تغمير -10'
                ]
        ];
    }
}
