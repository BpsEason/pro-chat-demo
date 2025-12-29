<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * 統一成功回傳格式
     * 🚀 展現架構一致性：確保前端永遠能從 data 屬性取得業務資料
     */
    protected function success(mixed $data, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * 統一失敗回傳格式
     * 🚀 有助於前端攔截器 (Interceptors) 進行統一的錯誤提示彈窗處理
     */
    protected function error(string $message = 'Error', int $code = 400, mixed $details = null): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $details,
        ], $code);
    }
}
