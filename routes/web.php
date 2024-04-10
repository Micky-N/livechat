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
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Volt::route('/rooms', 'rooms.index')->name('rooms.index');
    Volt::route('/friends', 'friends.index')->name('friends.index');
    Volt::route('/messages/rooms/{room}', 'messages.room')->name('rooms.messages');
    Volt::route('/messages/friends/{friend}', 'messages.friend')->name('friends.messages');
});
