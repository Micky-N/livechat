<?php

use function Livewire\Volt\{state, mount, computed, layout, on};
use App\Models\Message;

layout('layouts.app');
state(['friend' => fn(\App\Models\Friend $friend) => $friend, 'messages']);

on([
    'refresh-messages' => function (){
        $this->refreshMessages();
    },
    'delete-message' => function (Message $message) {
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

$friends = computed(fn() => auth()->user()->friends()->map(fn (\App\Models\User $user) => $user->pivot));

$friendsIds = computed(fn () => auth()->user()->friends()->map(fn (\App\Models\User $friend) => $friend->id)->merge([auth()->id()]));

$otherUser = computed(fn () => $this->friend->getOtherUser(auth()->user()));

$send = function (string $content, int $replyTo = null) {
    $newMessage = $this->friend->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
        'reply_to' => $replyTo
    ]);

    $this->addMessage($newMessage);

    broadcast(new \App\Events\GotMessage($newMessage))->toOthers();
};

$addMessage = function (Message $newMessage) {
    $this->messages->prepend($newMessage);
};

$resetMessages = function () {
    $this->messages = $this->friend->messages()
        ->where('user_id', $this->friend->getOtherUser(auth()->user())->id)
        ->get()->merge($this->friend->messages()->where('user_id', auth()->id())->get())->sortByDesc('created_at');
};

$removeMessage = function (int $messageId) {
    $this->resetMessages();
    $this->messages = $this->messages->filter(function (Message $message) use ($messageId) {
        return $message->id != $messageId;
    });
};

mount(function () {
    $this->messages = $this->friend->messages()
        ->where('user_id', $this->friend->getOtherUser(auth()->user())->id)
        ->get()->merge($this->friend->messages()->where('user_id', auth()->id())->get())->sortByDesc('created_at');
    $this->dispatch('user-with.' . $this->friend->id);
});

?>

<div class="h-full overflow-hidden bg-black/40" x-init="
    Echo.private('friend.{{ $friend->id }}')
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
    <livewire:friends.layout :subtitle="$this->otherUser->login" :friends="$this->friends" />
    @include('livewire.components.messages-container', ['messages' => $messages])

    <livewire:friends.components.add :friends-ids="$this->friendsIds" />
</div>
