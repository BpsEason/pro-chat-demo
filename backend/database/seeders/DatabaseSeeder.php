<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 建立一個固定帳號供 Demo 登入使用
        $agent = User::factory()->create([
            'name' => '專業客服 (Demo)',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. 建立一些測試客戶
        $customers = User::factory(10)->create();

        // 3. 隨機生成一些初始對話紀錄
        foreach ($customers as $customer) {
            Message::factory(5)->create([
                'sender_id' => $customer->id,
                'receiver_id' => $agent->id,
            ]);
        }

        echo "✅ Demo 數據初始化完成！\n";
        echo "📧 帳號: admin@demo.com\n";
        echo "🔑 密碼: password123\n";
    }
}
