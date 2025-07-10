<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class FinalResponse implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), [ 'إدارة المرافق',
            'الصيانة والتشغيل',
            'اللاند سكيب',
            'طلب أخر' , 'Facility Management',
            'Landscape',
            'Maintenance and Operation',
            'Other Request' ,    'Individuals',
            'Developers' ,  'المبيعات',
            'خدمات المطورين', ]);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Please send your name, phone number and email."
                : "  يرجي ارسال الاسم ورقم الهاتف والبريد الالكتروني ",
        ];
    }
}
