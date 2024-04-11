<?php

use function Livewire\Volt\{state, mount, computed, layout};

layout('layouts.app');
state(['friend' => fn(\App\Models\User $friend) => $friend, 'messages']);

$friends = computed(fn() => $user->personalTeam()->users);

$send = function (string $content) {
    $newMessage = $this->friend->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
    ]);

    $this->messages = auth()->user()->messages()
        ->where('user_id', $this->friend->id)
        ->get()->merge($this->friend->messages()->where('user_id', auth()->id())->get());

    $this->dispatch('message-created');
};

mount(function () {
    $this->messages = auth()->user()->messages()
        ->where('user_id', $this->friend->id)
        ->get()->merge($this->friend->messages()->where('user_id', auth()->id())->get());
    $this->dispatch('user-with.' . $this->friend->id);
});

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:friends.layout :friends="$this->friends" />
    @include('livewire.messages.container', ['messages' => $messages])
</div>
