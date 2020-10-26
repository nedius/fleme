<?php

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

// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// Broadcast::channel('users', function ($user) {
//     return auth()->check();
// });

Broadcast::channel('client', function ($user) {
    // dd($user->canJoinChannel($channelId));
    // dd($user->channels);
    // dd($user->channels->where('id', $channelId)->count());
    // return $user->channels->where('id', $channelId)->count(); //has($channelId); //->canJoinChannel($channelId);
    return auth()->check();
});