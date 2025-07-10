<?php

namespace App\Services\ChatBot\Responses;

use App\Services\ChatBot\ChatResponseInterface;

class WelcomeResponse implements ChatResponseInterface
{

    public function matches(string $message, ?string $lang): bool
    {
        return in_array(mb_strtolower(trim($message)), ['مرحبا', 'hi']);
    }

    public function reply(string $lang): array
    {
        return [
            'message' =>  $lang === 'en'
                ? "Welcome! Please choose a language:"
                : "مرحبًا بك! اختر اللغة: ",
            'choose' => [
                'العربي',
                'الإنجليزي'
            ]
        ];
    }
}
