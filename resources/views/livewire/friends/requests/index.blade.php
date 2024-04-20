<?php

use function Livewire\Volt\{state, layout, mount};

layout('layouts.app');

state(['requests' => collect()]);

mount(function () {
    /** @var \App\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();
    $this->requests = $user->pendingFriendsFrom;
});

?>

<div>
    <x-slot:title>
        Friend request
    </x-slot:title>
    <div class="max-w-2xl mx-auto p-4">
        <div class="items-start justify-between sm:flex">
            <div>
                <h4 class="text-neutral-200 text-xl font-semibold">Friends</h4>
                <p class="mt-2 text-neutral-400 text-base sm:text-sm">List of your friends, you can make a friend
                    request</p>
            </div>
            <x-primary-button wire:click="$dispatch('add-friends')"
               class="!py-1.5 mt-4 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                </svg>
                Add friend
            </x-primary-button>
        </div>
        <ul class="mt-12 divide-y">
            @forelse($requests as $request)
                <li class="py-5 flex items-start justify-between">
                    <div class="flex gap-3">
                        <img src="{{ $request->profile_photo_url }}" alt="{{ $request->login }}" class="flex-none w-12 h-12 rounded-full"/>
                        <div>
                            <span class="block text-sm text-white font-semibold truncate break-all">{{ $request->login }}</span>
                            <span class="block text-sm text-neutral-400 truncate">{{ $request->email }}</span>
                        </div>
                    </div>
                    <button wire:click="$dispatch('handle-request', {request: {{ $request->id }}})"
                       class="text-neutral-200 text-sm border rounded-lg px-3 py-2 duration-150 bg-neutral-800 hover:bg-neutral-900">Manage</button>
                </li>
            @empty
                <p class="py-5 text-center text-white border-b">
                    No friend
                </p>
            @endforelse
        </ul>
    </div>
    <livewire:friends.requests.form/>
    <livewire:friends.components.add/>
</div>
