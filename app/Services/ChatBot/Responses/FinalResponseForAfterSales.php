<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class FinalResponseForAfterSales implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), [
            'Wreef Apartment 40',
            'Aseel-1',
            'Abadie-1',
            'Kada-1',
            'Taghmeer-10' ,
            'وريف أبارتمنت 40',
            'أصيل -1',
            'أبادي -1',
            'كدا -1',
            'تغمير -10']);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Please send the name, phone number, project name, unit number, and request description."
                : " يرجي ارسال الاسم ورقم الهاتف واسم المشروع رقم الوحدة وصف الطلب  ",
        ];
    }
}
