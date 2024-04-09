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
    Volt::route('/dm', 'dm.index')->name('dm.index');
    Volt::route('/messages/rooms/{room}', 'messages.room')->name('rooms.messages');
    Volt::route('/messages/dm/{user}', 'messages.dm')->name('dm.messages');
});
