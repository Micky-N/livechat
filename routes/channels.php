<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('friend-message.{friendId}', function (\App\Models\User $user, int $friendId) {
    return $user->friends()->contains('id', $friendId);
});
