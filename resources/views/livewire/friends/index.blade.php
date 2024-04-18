<?php

use function Livewire\Volt\{state, layout, mount, computed};

layout('layouts.app');

state(['friends' => collect()]);

$friendsIds = computed(fn () => auth()->user()->friends()->map(fn (\App\Models\User $friend) => $friend->id)->merge([auth()->id()]));

mount(function () {
    $this->friends = auth()->user()->friends()->map(fn (\App\Models\User $friend) => $friend->pivot);
});

?>

<div>
    <livewire:friends.layout subtitle="All friends" :friends="$this->friends" />
    <div class="p-6 lg:p-8">
        <ul class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->friends as $friend)
                @include('livewire.friends.components.friends-item', ['user' => $friend->getOtherUser(auth()->user())])
            @endforeach
        </ul>
    </div>

    <livewire:friends.components.add :friends-ids="$this->friendsIds" />

    <livewire:friends.components.remove />
</div>
