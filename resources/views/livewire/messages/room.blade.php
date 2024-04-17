<?php

use App\Models\Message;
use function Livewire\Volt\{computed, layout, mount, state, on};

layout('layouts.app');
state(['room' => fn(\App\Models\Team $room) => $room, 'messages']);

on([
    'delete-message' => function (\App\Models\Message $message) {
        if ($message->replies) {
            foreach ($message->replies as $reply) {
                $this->dispatch('remove-reply.' . $reply->id);
            }
        }
        broadcast(new \App\Events\RemoveMessage($message))->toOthers();

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

    $this->addMessage($newMessage);

    broadcast(new \App\Events\GotMessage($newMessage))->toOthers();
};

$resetMessages = fn() => ($this->messages = $this->room->messages->sortByDesc('created_at'));

$addMessage = function (Message $newMessage) {
    $this->messages->prepend($newMessage);
};

$removeMessage = function (int $messageId) {
    $this->resetMessages();
    $this->messages = $this->messages->filter(function (Message $message) use ($messageId) {
        return $message->id != $messageId;
    });
};

mount(function () {
    $this->messages = $this->room->messages->sortByDesc('created_at');
    $this->dispatch('user-in-room.' . $this->room->id);
});

?>

<div class="h-full overflow-hidden bg-black/40" x-init="
    Echo.private('room.{{ $room->id }}')
        .listen('.got-message', (e) => {
            $wire.addMessage(e.id);
        })
        .listen('.update-message', (e) => {
            console.log(e);
            $wire.$dispatch('update-message.' + e.id, {content: e.content});
        })
        .listen('.remove-message', (e) => {
            $wire.removeMessage(e.id)
        })
">
    <livewire:rooms.layout :rooms="$this->rooms"/>
    @include('livewire.messages.container', ['messages' => $messages])
</div>
