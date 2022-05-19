<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat.{chat_id}', function ($user, $chat_id) {
    if(Auth::check()){
        return true;
    }
    return false;
});

Broadcast::channel('user.{user_id}', function ($user, $user_id) {
    if(Auth::check()){
        return true;
    }
    return false;
});
