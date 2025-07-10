<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Services\ChatBot\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{
// ChatController
    public function send(Request $request)
    {
        $message = $request->input('message');
        ChatMessage::create([
            'user_id' => auth('customer')->id(),
            'message' => $message,
            'sender' => 'user',
        ]);

        // معالجة الرد حسب الرسالة
        $response = ChatBotService::generateResponse($message , auth('customer')->id());

        ChatMessage::create([
            'user_id' => auth('customer')->id(),
            'message' => $response['message'],
            'sender' => 'bot',
        ]);

        return Response::success(['reply' => $response]);
    }

}
