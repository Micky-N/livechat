<?php

use function Livewire\Volt\{computed, layout};

layout('layouts.app');

/** @var \App\Models\User $user */
$user = \Illuminate\Support\Facades\Auth::user();

$rooms = computed(fn() => $user->allTeams());

?>

<div>
    <livewire:rooms.layout :rooms="$this->rooms" />
    <div class="p-6 lg:p-8">
        <div class="grid grid-cols-1 gap-y-4 gap-x-4 text-center sm:text-left lg:grid-cols-2 xl:grid-cols-3">
            @foreach($this->rooms as $room)
                <livewire:rooms.components.rooms-item :$room/>
                <livewire:rooms.components.rooms-item :$room/>
                <livewire:rooms.components.rooms-item :$room/>
            @endforeach
        </div>
    </div>
</div>
