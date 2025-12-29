<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// 🚀 關鍵：將 chat 頻道的定義註解或刪除，Laravel 就會將其視為公開頻道
/*
Broadcast::channel('chat', function () {
    return true;
});
*/