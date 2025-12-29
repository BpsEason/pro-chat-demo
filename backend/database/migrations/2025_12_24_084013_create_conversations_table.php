<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name'); // 訪客顯示名稱
            $table->string('visitor_id')->unique(); // 訪客唯一識別碼 (Session/Cookie ID)
            $table->string('last_message')->nullable(); // 冗餘儲存最後一條訊息，優化列表讀取效能
            $table->integer('unread_count')->default(0); // 未讀計數
            $table->timestamp('last_message_at')->nullable(); // 排序用
            $table->timestamps();

            // 索引優化：客服列表通常依據最後訊息時間排序
            $table->index('last_message_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
