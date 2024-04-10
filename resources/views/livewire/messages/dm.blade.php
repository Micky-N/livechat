<?php

use function Livewire\Volt\{state, mount, computed, layout};

layout('layouts.app');
state(['friend' => fn(\App\Models\User $user) => $user]);

/** @var \App\Models\User $user */
$user = \Illuminate\Support\Facades\Auth::user();

$friends = computed(fn() => $user->personalTeam()->users);

$send = function (string $content) {
    $newMessage = $this->room->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content,
    ]);

    $this->friend->messages->push($newMessage);

    $this->dispatch('message-created');
};

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:dm.layout :friends="$this->friends" />
    @include('livewire.messages.container', ['messages' => $friend->messages()->orderBy('created_at')->get()])
</div>
