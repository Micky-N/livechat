<?php

use function Livewire\Volt\{state, layout, mount};

layout('layouts.app');

state(['friends' => collect()]);

mount(function () {
    /** @var \App\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();
    $this->friends = $user->friends();
});

?>

<div>
    <livewire:friends.layout :friends="$this->friends" />
    <div class="p-6 lg:p-8">
        <ul class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->friends as $friend)
                @include('livewire.friends.components.friends-item')
            @endforeach
        </ul>
    </div>

    <livewire:friends.components.add />

    <livewire:friends.components.remove />
</div>
