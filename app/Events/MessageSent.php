<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        // لو مرسل الرسالة "agent" أو "bot"، ابعت للـ customer
        if (in_array($this->message->sender_type, ['agent', 'bot'])) {
            return new PrivateChannel('chat.conversation.' . $this->message->thread->customer_id);
        }

        // لو مرسل الرسالة "customer"، ابعت للـ admin
        return new PrivateChannel('admin'); // أو مثلاً admin.1 لو فيه عدة مشرفين
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'thread_id' => $this->message->thread_id,
            'sender_type' => $this->message->sender_type,
            'sender_id' => $this->message->sender_id,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
