<?php

use function Livewire\Volt\{state, mount, computed, layout, on};
use App\Models\Message;

layout('layouts.app');
state(['friend' => fn(\App\Models\User $friend) => $friend, 'messages']);

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
        $message->delete();
        $this->resetMessages();
    }
]);

$friends = computed(fn() => auth()->user()->personalTeamUsers());

$send = function (string $content, int $replyTo = null) {
    $newMessage = $this->friend->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
        'reply_to' => $replyTo
    ]);

    $this->addMessage($newMessage);

    \App\Events\Friend\GotMessage::dispatch($newMessage);
};

$addMessage = function (Message $newMessage) {
    $this->messages->prepend($newMessage);
};

$resetMessages = function () {
    $this->messages = auth()->user()->messages()
        ->where('user_id', $this->friend->id)
        ->get()->merge($this->friend->messages()->where('user_id', auth()->id())->get())->sortByDesc('created_at');
};

mount(function () {
    $this->messages = auth()->user()->messages()
        ->where('user_id', $this->friend->id)
        ->get()->merge($this->friend->messages()->where('user_id', auth()->id())->get())->sortByDesc('created_at');

    $this->dispatch('user-with.' . $this->friend->id);
});

?>

<div class="h-full overflow-hidden bg-black/40" x-init="
    Echo.channel('channel-name')
        .listen('.friend-got-message', (e) => {
            $wire.addMessage(e.id);
        })
">
    <livewire:friends.layout :friends="$this->friends" />
    @include('livewire.messages.container', ['messages' => $messages])
</div>
