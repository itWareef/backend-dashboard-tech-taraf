<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Services\ChatBot\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function PlantingDataChat(Request $request)
    {
        $data = QueryBuilder::for(ChatMessage::class)->whereIn('message' ,['Landscape' ,'اللاند سكيب'])->paginate(20);
        return Response::success($data);
    }
    public function serviceDataChat(Request $request)
    {
        $data = QueryBuilder::for(ChatMessage::class)->whereIn('message' ,[  'إدارة المرافق',
            'الصيانة والتشغيل',
            'طلب أخر'
        , 'Facility Management',
            'Maintenance and Operation',
            'Other Request'])->paginate(20);
        return Response::success($data);
    }
    public function salesDataChat(Request $request)
    {
        $data = QueryBuilder::for(ChatMessage::class)->whereIn('message' ,[ 'Sales','المبيعات'])->paginate(20);
        return Response::success($data);
    }
}
