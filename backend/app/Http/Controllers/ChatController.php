<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * 獲取或初始化對話紀錄
     * 🚀 架構亮點：結合 Transaction 強制 Master 讀取與統一回傳格式
     */
    public function getMessages(Request $request): JsonResponse
    {
        $visitorId = $request->query('visitor_id');
        $username = $request->query('username');

        if (!$visitorId) {
            return $this->error('Visitor ID is required', 400);
        }

        // 使用 Transaction 確保在讀寫分離架構下，刷新後能立即從 Master 讀到最新資料
        return DB::transaction(function () use ($visitorId, $username) {

            $conversation = Conversation::firstOrCreate(
                ['visitor_id' => $visitorId],
                [
                    'visitor_name' => $username ?? '訪客' . substr($visitorId, -4),
                    'last_message' => '訪客進入頁面',
                    'last_message_at' => now(),
                    'unread_count' => 0
                ]
            );

            // 強制從 Master 獲取最新的訊息紀錄
            $messages = Message::where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'asc')
                ->get();

            // 🚀 使用基底 Controller 的 success 方法，將數據包裹在 data 屬性中
            return $this->success([
                'conversation' => $conversation,
                'messages' => $messages
            ], '對話紀錄載入成功');
        });
    }

    /**
     * 訪客發送訊息
     * 🚀 架構亮點：鎖定行紀錄 (Pessimistic Locking) 確保未讀計數在高併發下正確
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'visitor_id' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {

            // lockForUpdate 確保 unread_count 更新時不會發生 Race Condition
            $conversation = Conversation::where('visitor_id', $request->visitor_id)
                ->lockForUpdate()
                ->first();

            if (!$conversation) {
                return $this->error('Conversation not found', 404);
            }

            $message = $conversation->messages()->create([
                'sender_id' => 1,
                'receiver_id' => 1,
                'content' => $request->message,
                'sender_type' => 'visitor',
                'is_read' => false,
            ]);

            // 更新摘要，維持冗餘欄位以優化後台列表讀取效能
            $conversation->update([
                'last_message' => $request->message,
                'unread_count' => $conversation->unread_count + 1,
                'last_message_at' => now(),
            ]);

            // 將訊息廣播到 'chat' 頻道 (與 MessageSent.php 的 broadcastOn 對應)
            broadcast(new \App\Events\MessageSent($message))->toOthers();

            // 🚀 統一回傳格式，方便前端處理
            return $this->success($message, '訊息發送成功');
        });
    }
}
