<?php

use function Livewire\Volt\{state, mount, computed, layout};

layout('layouts.app');
state(['friend' => fn(\App\Models\User $friend) => $friend]);

/** @var \App\Models\User $user */
$user = \Illuminate\Support\Facades\Auth::user();

$friends = computed(fn() => $user->personalTeam()->users);

$send = function (string $content) {
    $newMessage = $this->friend->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
    ]);

    $this->friend->messages->push($newMessage);

    $this->dispatch('message-created');
};

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:friends.layout :friends="$this->friends" />
    @include('livewire.messages.container', ['messages' => $friend->messages()->orderBy('created_at', 'desc')->get()])
</div>
