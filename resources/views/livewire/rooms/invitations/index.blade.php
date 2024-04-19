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
    <div class="p-6 lg:p-8">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8">
            <x-secondary-button wire:click="$dispatch('join-room')">
                Join a room
            </x-secondary-button>
            @foreach ($this->rooms as $room)
                    <div class="mt-12 shadow-sm border rounded-lg overflow-x-auto">
                        <table class="w-full table-auto text-sm text-left">
                            <thead class="bg-neutral-950/75 text-gray-100 font-medium border-b">
                                <tr>
                                    <th colspan="2" class="py-3 px-6 text-center text-base">{{ $room->name }}</th>
                                </tr>
                                <tr>
                                    <th class="py-3 px-6">Requester</th>
                                    <th class="py-3 px-6"></th>
                                </tr>
                            </thead>
                            <tbody class="text-neutral-100 divide-y">
                                @forelse($room->teamInvitations as $teamInvitation)
                                    <tr>
                                        <td class="flex items-center gap-x-3 py-3 px-6 whitespace-nowrap">
                                            <img src="{{ $teamInvitation->user->profile_photo_url }}"
                                                class="w-10 h-10 rounded-full" />
                                            <div>
                                                <span class="block text-sm font-medium">
                                                    {{ $teamInvitation->user->login }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-right py-3 px-6 whitespace-nowrap">
                                            <button
                                                wire:click="$dispatch('handle-request', {request: {{ $teamInvitation->id }}})"
                                                class="py-1.5 px-3 bg-white text-gray-600 hover:text-gray-500 duration-150 hover:bg-gray-100 rounded-lg">
                                                Manage
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-lg py-3">
                                            No room request
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endforeach
        </div>
    </div>

    <livewire:rooms.invitations.join />

    <livewire:rooms.invitations.form />
</div>
