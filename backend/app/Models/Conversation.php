<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'visitor_name',
        'visitor_id',
        'last_message',
        'unread_count',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // 一個會話擁有多條訊息
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
