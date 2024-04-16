<?php

use function Livewire\Volt\{state, mount, computed, layout, on};

layout('layouts.app');
state(['friend' => fn(\App\Models\User $friend) => $friend, 'messages']);

on([
    'refresh-messages' => function (){
        $this->refreshMessages();
    }
]);

$friends = computed(fn() => auth()->user()->personalTeamUsers());

$send = function (string $content, int $replyTo = null) {
    $newMessage = $this->friend->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
        'reply_to' => $replyTo
    ]);

    $this->refreshMessages();

    \App\Events\Friend\GotMessage::dispatch($newMessage);

    $this->dispatch('message-created');
};

$refreshMessages = function () {
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
            console.log(e);
        })
">
    <livewire:friends.layout :friends="$this->friends" />
    @include('livewire.messages.container', ['messages' => $messages])
</div>
