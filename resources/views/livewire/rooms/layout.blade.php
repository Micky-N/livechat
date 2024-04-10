<?php

use function Livewire\Volt\{state};

state('rooms');

?>

<div>
    <x-slot:title-menu>
        @volt()
            <div class="flex items-center justify-between">
                <span>Rooms</span>
                <button type="button" wire:click="$dispatch('add-room')"
                    class="text-white py-1 px-2 bg-gray-500 rounded-md flex items-center hover:bg-orange-400 focus:bg-orange-400 focus:ring-4 focus:ring-orange-400/40 focus:outline-none text-center">
                    <span>New Room</span>
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
        @foreach ($rooms as $room)
            <a href="{{ route('rooms.messages', ['room' => $room->id]) }}"
                class="flex cursor-pointer items-center {{ request()->is('messages/rooms/' . $room->id) ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mr-4 h-5 w-5 align-middle">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                </svg>

                {{ $room->name }}
                @if ($count = $room->unReadMessages()->count())
                    <span class="ml-auto rounded-full bg-orange-500 px-2 text-xs text-white">{{ $count }}</span>
                @endif
            </a>
        @endforeach
    </x-slot:menu>
</div>
