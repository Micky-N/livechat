<?php

use App\Models\Friend;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('friend.{friend}', function (User $user, Friend $friend) {
    return $friend->user_id == $user->id || $friend->friend_id == $user->id;
});

Broadcast::channel('room.{room}', function (User $user, Team $room) {
    if ($user->canJoinRoom($room)) {
        return ['id' => $user->id, 'login' => $user->login];
    }

    return null;
});
