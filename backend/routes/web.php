<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. 基礎頁面 ---
Route::get('/', function () {
    return view('welcome');
});

// Breeze 預設的 Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 2. 訪客端 API (免登入) ---
Route::prefix('api')->group(function () {
    // 獲取訊息：GET /api/messages?visitor_id=xxx
    Route::get('/messages', [ChatController::class, 'getMessages']);
    // 發送訊息：POST /api/messages
    Route::post('/messages', [ChatController::class, 'send']);
});


// --- 3. 客服管理端 API (受 Breeze 認證保護) ---
// 這裡掛載了 'auth'，會自動檢查用戶是否透過 /login 登入
Route::prefix('api/admin')->middleware(['auth'])->group(function () {

    // 取得所有會話列表
    Route::get('/conversations', [AdminChatController::class, 'getConversations']);

    // 進入特定對話 (取得歷史訊息)
    Route::get('/conversations/{conversation}/messages', [AdminChatController::class, 'getMessages']);

    // 客服回覆訊息
    Route::post('/conversations/{conversation}/reply', [AdminChatController::class, 'sendReply']);
});


// --- 4. Breeze 用戶個人資料管理
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- 5. 引入 Breeze 認證邏輯 ---
// 這行會自動載入 routes/auth.php，包含 /login, /register, /logout 等路由
require __DIR__ . '/auth.php';
