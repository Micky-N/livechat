<?php

use function Livewire\Volt\{computed, layout, mount, state};

layout('layouts.app');
state(['room' => fn(\App\Models\Team $room) => $room]);

/** @var \App\Models\User $user */
$user = \Illuminate\Support\Facades\Auth::user();

$rooms = computed(fn() => $user->allTeams());

$send = function (string $content) {
    $newMessage = $this->room->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
    ]);

    $this->room->messages->push($newMessage);

    $this->dispatch('message-created');
};

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:rooms.layout :rooms="$this->rooms" />
    @include('livewire.messages.container')
</div>
