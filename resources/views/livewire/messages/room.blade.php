<?php

use App\Models\Message;
use function Livewire\Volt\{computed, layout, mount, on, state};

layout('layouts.app');
state(['room' => fn(\App\Models\Team $room) => $room, 'messages', 'usersConnected' => [auth()->id()]]);

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
    },
    'here' => function (array $users) {
        $this->usersConnected = array_map(fn (array $user) => $user['id'], $users);
    },
    'joining' => function (array $user) {
        $this->usersConnected[] = $user['id'];
    },
    'leaving' => function (array $user) {
        $this->usersConnected = array_filter($this->usersConnected, fn($u) => $u !== $user['id']);
    },
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

$members = computed(function () {
    return $this->room->users->sortBy('login')
        ->map(function (\App\Models\User $user) {
            $user->connected = in_array($user->id, $this->usersConnected);
            return $user;
        });
});

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
    Echo.join('room.{{ $room->id }}')
        .here((users) => {
            $wire.$dispatch('here', {users});
        })
        .joining((user) => {
            $wire.$dispatch('joining', {user});
            console.log(user.login);
        })
        .leaving((user) => {
            $wire.$dispatch('leaving', {user});
        })
        .error((error) => {
            console.error(error);
        })
        .listen('GotMessage', (e) => {
            $wire.addMessage(e.id);
        })
        .listen('UpdateMessage', (e) => {
            console.log(e);
            $wire.$dispatch('update-message.' + e.id, {content: e.content});
        })
        .listen('RemoveMessage', (e) => {
            $wire.removeMessage(e.id)
        })
">
    <livewire:rooms.layout :rooms="$this->rooms"/>
    @include('livewire.messages.container', ['messages' => $messages])
</div>
