<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // 🚀 修正點 1: 確保 conversation_id 放在 id 之後
            $table->foreignId('conversation_id')
                ->after('id')
                ->nullable()
                ->constrained('conversations')
                ->onDelete('cascade');

            // 🚀 修正點 2: 將 sender_type 放在 content 之後 (原代碼錯寫為 message)
            $table->string('sender_type')
                ->default('visitor')
                ->after('content'); // 修改這裡，對齊您第一個 Migration 的 content 欄位

            // 增加索引以加速客服調取歷史紀錄
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn(['conversation_id', 'sender_type']);
        });
    }
};
