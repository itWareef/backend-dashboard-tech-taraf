<?php

namespace App\Services\ChatBot;

use App\Services\ChatBot\Responses\FinalResponse;
use App\Services\ChatBot\Responses\FinalResponseForAfterSales;
use App\Services\ChatBot\Responses\LanguageSelectionResponse;
use App\Services\ChatBot\Responses\ServiceAfterSalesSelectionResponse;
use App\Services\ChatBot\Responses\ServiceContractServiceSelectionResponse;
use App\Services\ChatBot\Responses\ServiceSalesSelectionResponse;
use App\Services\ChatBot\Responses\WelcomeResponse;
use Illuminate\Support\Facades\Cache;

class ChatBotService
{
    protected static array $responses = [
        WelcomeResponse::class,
        LanguageSelectionResponse::class,
        ServiceSalesSelectionResponse::class,
        ServiceContractServiceSelectionResponse::class,
        ServiceAfterSalesSelectionResponse::class,
        ServiceAfterSalesSelectionResponse::class,
        FinalResponse::class,
        FinalResponseForAfterSales::class
    ];

    public static function generateResponse(string $message, int $userId): array
    {
        $lang = Cache::get("chatbot_lang_" . $userId);
        $state = Cache::get("chatbot_state_\$userId");

        // لو كانت الرسالة هي اختيار اللغة، نخزنها
        if (in_array(mb_strtolower($message), ['arabic', 'العربي'])) {
        $lang = 'ar';
            Cache::put("chatbot_lang_" . $userId, 'ar', now()->addHours(6));
        } elseif (in_array(mb_strtolower($message), ['english', 'الإنجليزي'])) {
        $lang = 'en';
            Cache::put("chatbot_lang_" . $userId, 'en', now()->addHours(6));
        }

        if ($state === 'awaiting_user_info') {
            $pattern = '/(.+?)\s*-\s*(\d{10,})\s*-\s*([\w.%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,})/u';
            if (preg_match($pattern, $message, $matches)) {
                $name = $matches[1];
                $phone = $matches[2];
                $email = $matches[3];

                Cache::forget("chatbot_state_$userId");

                return ['message' =>$lang === 'en'
                    ? "Dear customer, thank you for contacting us. We will respond to you as soon as possible."
                    : "عميلنا العزيز نشكرك علي تواصلك معنا وسيتم الرد عليك في أقرب وقت ممكن"];
            }

            return [
                'message' =>$lang === 'en'
                    ? "Please enter your name - phone - email separated by dashes (-)."
                    : "يرجى إدخال الاسم - رقم الهاتف - البريد الإلكتروني مفصولين بشرطتين (-)."
            ];
        }

// الحالة: بيانات ما بعد البيع
        if ($state === 'awaiting_after_sales_data') {
            $pattern = '/(.+?)\s*-\s*(\d{10,})\s*-\s*(.+?)\s*-\s*(.+?)\s*-\s*(.+)/u';
            if (preg_match($pattern, $message, $matches)) {
                $name    = $matches[1];
                $phone   = $matches[2];
                $project = $matches[3];
                $unit    = $matches[4];
                $desc    = $matches[5];

                Cache::forget("chatbot_state_$userId");

                return [
                    'message' => $lang === 'en'
                        ? "Dear customer, thank you for contacting us. We will respond to you as soon as possible."
                        : "عميلنا العزيز نشكرك علي تواصلك معنا وسيتم الرد عليك في أقرب وقت ممكن"
                ];
            }

            return [
                'message' =>$lang === 'en'
                    ? "Please enter: name - phone - project name - unit number - request description"
                    : "يرجى إدخال: الاسم - رقم الهاتف - اسم المشروع - رقم الوحدة - وصف الطلب"
            ];
        }

        foreach (self::$responses as $responseClass) {
        /** @var ChatResponseInterface $response */
        $response = new $responseClass();
            if ($response->matches($message, $lang)) {
            return $response->reply($lang ??'');
            }
            if ($response instanceof \App\Services\ChatBot\Responses\FinalResponse) {
                Cache::put("chatbot_state_$userId", 'awaiting_user_info', now()->addHours(6));
            }
            if ($response instanceof FinalResponseForAfterSales) {
                Cache::put("chatbot_state_$userId", 'awaiting_after_sales_data', now()->addHours(6));
            }
        }

        return ['message' =>$lang === 'en'
        ? "Dear customer, thank you for contacting us. We will respond to you as soon as possible."
        : "عميلنا العزيز نشكرك علي تواصلك معنا وسيتم الرد عليك في أقرب وقت ممكن"];
    }
}
