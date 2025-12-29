<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel; // 🚀 關鍵：確保引入的是 Channel
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // 🚀 關鍵：定義一個 public $data，Laravel 會自動把它轉成 JSON 送給前端
    public $data;

    public function __construct(Message $message)
    {
        $this->data = [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'content' => $message->content,
            'sender_type' => $message->sender_type,
            'created_at' => $message->created_at->toDateTimeString(),
        ];
    }

    public function broadcastOn(): array
    {
        // 🚀 關鍵：使用 new Channel() 來定義廣播到一個公開頻道
        return [new Channel('chat')];
    }

    // 🚀 加上這個方法，確保前端收到的事件名稱不帶命名空間，增加相容性
    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
