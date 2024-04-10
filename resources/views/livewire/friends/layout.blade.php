<?php

use function Livewire\Volt\{state};

state('friends');

?>

<div>
    <x-slot:title-menu>
        @volt()
            <div class="flex items-center justify-between">
                <span>Friends</span>
                <button type="button" wire:click="$dispatch('add-friends')"
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
            <a href="{{ route('friends.messages', ['friend' => $friend->id]) }}"
                class="flex cursor-pointer items-center {{ request()->is('messages/friends/' . $friend->id) ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mr-4 h-5 w-5 align-middle">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>

                {{ $friend->login }}
                @if ($count = $friend->unReadMessages()->count())
                    <span class="ml-auto rounded-full bg-orange-500 px-2 text-xs text-white">{{ $count }}</span>
                @endif
            </a>
        @endforeach
    </x-slot:menu>
</div>
