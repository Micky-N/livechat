<?php

use function Livewire\Volt\{computed, layout, mount, state};

layout('layouts.app');
state(['room' => fn (\App\Models\Team $room) => $room]);

/** @var \App\Models\User $user */
$user = \Illuminate\Support\Facades\Auth::user();

$rooms = computed(fn() => $user->allTeams());

?>

<div>
    <livewire:rooms.layout :rooms="$this->rooms" />
</div>
