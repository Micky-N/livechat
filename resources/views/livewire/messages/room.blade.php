<?php

use function Livewire\Volt\{computed, layout, mount, state, on};

layout('layouts.app');
state(['room' => fn(\App\Models\Team $room) => $room]);

on([
    'delete-message' => function (\App\Models\Message $message) {
        if ($message->replies) {
            foreach ($message->replies as $reply) {
                $this->dispatch('remove-reply.' . $reply->id);
            }
        }
        $message->delete();
        $this->resetMessages();
    }
]);

$rooms = computed(fn() => auth()->user()->allTeams());

$send = function (string $content, int $replyTo = null) {
    $newMessage = $this->room->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
        'reply_to' => $replyTo
    ]);

    $this->resetMessages();

    $this->dispatch('message-created');
};

$resetMessages = fn() => ($this->room->messages = $this->room->messages()->get());

mount(function () {
    $this->dispatch('user-in-room.' . $this->room->id);
});

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:rooms.layout :rooms="$this->rooms" />
    @include('livewire.messages.container', ['messages' => $room->messages->sortByDesc('created_at')])
</div>
