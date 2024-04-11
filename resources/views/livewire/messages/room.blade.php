<?php

use function Livewire\Volt\{computed, layout, mount, state};

layout('layouts.app');
state(['room' => fn(\App\Models\Team $room) => $room]);

$rooms = computed(fn() => auth()->user()->allTeams());

$send = function (string $content) {
    $newMessage = $this->room->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
    ]);

    $this->room->messages = $this->room->messages()->orderBy('created_at')->get();

    $this->dispatch('message-created');
};

mount(function () {
    $this->dispatch('user-in-room.' . $this->room->id);
});

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:rooms.layout :rooms="$this->rooms" />
    @include('livewire.messages.container', ['messages' => $room->messages()->orderBy('created_at')->get()])
</div>
