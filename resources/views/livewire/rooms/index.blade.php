<?php

use function Livewire\Volt\{state, computed, layout, on, mount};

layout('layouts.app');

state(['rooms' => collect()]);

mount(function () {
    /** @var \App\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();
    $this->rooms = $user->allTeams();
});

?>
<div>
    <livewire:rooms.layout subtitle="All rooms" :rooms="$this->rooms" />
    <div class="p-6 lg:p-8">
        <div class="grid grid-cols-1 gap-y-4 gap-x-4 text-center sm:text-left lg:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->rooms as $room)
                <livewire:rooms.components.rooms-item wire:key="{{ $room->name . '-' . $room->id }}" :$room />
            @endforeach
        </div>
    </div>

    <livewire:rooms.components.form />

    <livewire:rooms.components.delete />
</div>
