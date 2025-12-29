<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class AdminChatController extends Controller
{
    /**
     * 取得所有對話列表
     * 🚀 高性能展現：此處不使用 Transaction，Laravel 會自動從 Slave 讀取，減輕 Master 負擔
     */
    public function getConversations(): JsonResponse
    {
        $conversations = Conversation::orderBy('last_message_at', 'desc')->get();
        return $this->success($conversations, '對話列表載入成功');
    }

    /**
     * 取得單一對話的訊息
     * 🚀 邏輯亮點：讀取的同時更新 Master 狀態 (未讀清零)
     */
    public function getMessages(Conversation $conversation): JsonResponse
    {
        return DB::transaction(function () use ($conversation) {
            // 客服點開時，將未讀數清零 (強迫寫入 Master)
            $conversation->update(['unread_count' => 0]);

            // 取得該會話的所有訊息
            $messages = $conversation->messages()
                ->orderBy('created_at', 'asc')
                ->get();

            return $this->success([
                'conversation' => $conversation,
                'messages' => $messages
            ], '訊息載入成功');
        });
    }

    /**
     * 客服回覆訊息
     * 🚀 架構亮點：確保資料一致性並對齊 Model 欄位
     */
    public function sendReply(Request $request, Conversation $conversation): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        return DB::transaction(function () use ($request, $conversation) {

            // 建立訊息紀錄
            $message = $conversation->messages()->create([
                'content'     => $request->message,
                'sender_type' => 'agent',
                'sender_id'   => Auth::id() ?? 1, // 取得當前登入客服 ID
                'receiver_id' => null, // 客服回給訪客
                'is_read'     => true,
            ]);

            // 更新會話摘要 (冗餘儲存以優化側欄讀取效能)
            $conversation->update([
                'last_message'    => $request->message,
                'last_message_at' => now(),
            ]);

            // 🚀 新增廣播邏輯
            broadcast(new \App\Events\MessageSent($message))->toOthers();

            return $this->success($message, '回覆發送成功');
        });
    }
}
