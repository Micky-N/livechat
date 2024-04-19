<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Volt::route('/rooms', 'rooms.index')->name('rooms.index');
    Volt::route('/rooms/invitations', 'rooms.invitations.index')->name('rooms.invitations.index');
    Volt::route('/rooms/{room}/messages', 'rooms.messages')->name('rooms.messages');
    Volt::route('/friends', 'friends.index')->name('friends.index');
    Volt::route('/friends/requests', 'friends.requests.index')->name('friends.requests.index');
    Volt::route('/friends/{friend}/messages', 'friends.messages')->name('friends.messages');
});
