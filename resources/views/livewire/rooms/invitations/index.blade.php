<?php

use function Livewire\Volt\{state, layout, mount};

layout('layouts.app');

state(['rooms' => collect()]);

mount(function () {
    /** @var \App\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();
    $this->rooms = $user->ownedTeams->load('teamInvitations.user');
});

?>

<div>
    <x-slot:title>
        Room request
    </x-slot:title>
    <div class="max-w-2xl mx-auto p-4">
        <div class="mx-auto">
            <x-secondary-button wire:click="$dispatch('join-room')">
                Join a room
            </x-secondary-button>

            @foreach ($this->rooms as $room)
                <div class="mt-12 shadow-sm overflow-x-auto border rounded-md">
                    <h4 class="text-lg text-center text-white bg-black/80">{{ $room->name }}</h4>
                    <ul class="divide-y px-2 sm:px-4">
                        @forelse($room->teamInvitations as $teamInvitation)
                            <li class="py-5 flex items-start justify-between">
                                <div class="flex gap-3">
                                    <img src="{{ $teamInvitation->user->profile_photo_url }}"
                                         alt="{{ $teamInvitation->user->login }}"
                                         class="flex-none w-12 h-12 rounded-full"/>
                                    <div>
                                        <span
                                            class="block text-sm text-white font-semibold truncate">{{ $teamInvitation->user->login }}</span>
                                        <span
                                            class="block text-sm text-neutral-400 truncate">{{ $teamInvitation->user->email }}</span>
                                    </div>
                                </div>
                                <button wire:click="$dispatch('handle-request', {request: {{ $teamInvitation->id }}})"
                                        class="text-neutral-200 text-sm border rounded-lg px-3 py-2 duration-150 bg-neutral-800 hover:bg-neutral-900">
                                    Manage
                                </button>
                            </li>
                        @empty
                            <p class="py-5 text-center text-white border-b">
                                No room invitation
                            </p>
                        @endforelse
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <livewire:rooms.invitations.join/>

    <livewire:rooms.invitations.form/>
</div>
