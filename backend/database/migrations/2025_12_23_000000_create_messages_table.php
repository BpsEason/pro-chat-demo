<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // 寄件者維持原樣（通常發送訊息的必須是系統內的某人，或是你在這也改 nullable）
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // 🚀 重點修改：允許 receiver_id 為空，並移除自動強制約束
            // 這樣當客服回覆訪客時，你可以傳入 null，而不會因為 users 表找不到 ID 0 而報錯
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
