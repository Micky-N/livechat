<?php

use function Livewire\Volt\{state};

state('friends');

?>

<div>
    <x-slot:title-menu>
        @volt()
            <div class="flex items-center justify-between">
                <span>Friends</span>
                <button type="button" wire:click="$dispatch('add-friend')"
                    class="text-white py-1 px-2 bg-gray-500 rounded-md flex items-center hover:bg-orange-400 focus:bg-orange-400 focus:ring-4 focus:ring-orange-400/40 focus:outline-none text-center">
                    <span>New Friend</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="ml-1 w-4 h-4">
                        <path fill-rule="evenodd" stroke="currentColor" stroke-width="2"
                            d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endvolt
    </x-slot:title-menu>
    <x-slot:menu>
        @foreach ($friends as $friend)
            <a href="{{ route('dm.messages', ['user' => $friend->id]) }}"
                class="flex cursor-pointer items-center {{ request()->is('messages/rooms/' . $friend->id) ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                <svg class="mr-4 h-5 w-5 align-middle" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>

                {{ $friend->login }}
                @if ($count = $friend->unReadMessages()->count())
                    <span class="ml-auto rounded-full bg-orange-500 px-2 text-xs text-white">{{ $count }}</span>
                @endif
            </a>
        @endforeach
    </x-slot:menu>
</div>
