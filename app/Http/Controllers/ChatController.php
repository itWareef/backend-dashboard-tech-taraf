<?php
namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatThread;
use App\Events\MessageSent;
use App\Services\ChatBot\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{
    // إرسال رسالة من العميل
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $user = auth('customer')->user();

        $thread = ChatThread::firstOrCreate(['customer_id' => $user->id]);

        $msg = ChatMessage::create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'sender_id' => $user->id,
            'sender_type' => 'customer',
            'message' => $request->message,
        ]);

        // بث الرسالة
        event(new MessageSent($msg));

        // رد البوت
        $botReply = ChatBotService::generateResponse($request->message, $user->id);

        $botMsg = ChatMessage::create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'sender_id' => 0,
            'sender_type' => 'bot',
            'message' => $botReply['message'],
        ]);

        event(new MessageSent($botMsg));

        return Response::success(['reply' => $botReply ,'is_bot'=>false]);
    }

    // رد من خدمة العملاء
    public function replyFromAgent(Request $request)
    {
        $request->validate([
            'thread_id' => 'required|exists:chat_threads,id',
            'message' => 'required|string',
        ]);

        $agent = auth('admin')->user();

        $msg = ChatMessage::create([
            'thread_id' => $request->thread_id,
            'sender_id' => $agent->id,
            'sender_type' => 'agent',
            'message' => $request->message,
        ]);

        event(new MessageSent($msg));

        return Response::success(['message' => 'تم إرسال الرد بنجاح']);
    }

    // عرض كل الرسائل في جلسة واحدة
    public function getThreadMessages($thread_id)
    {
        $messages = ChatMessage::where('thread_id', $thread_id)
            ->orderBy('created_at')
            ->get();

        return Response::success(['messages' => $messages]);
    }

    // عرض كل الجلسات لخدمة العملاء
    public function getAllThreads()
    {
        $threads = ChatThread::with('customer')->withCount('messages')->latest()->paginate(10);
        return Response::success(['threads' => $threads]);
    }
}
