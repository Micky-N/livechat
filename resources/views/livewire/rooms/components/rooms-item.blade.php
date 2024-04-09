<?php

use function Livewire\Volt\{state, computed};

state('room');

$owner = computed(fn() => $this->room->owner);

$deleteRoom = function (\App\Models\Team $room) {
    dd($room);
};

$deleteRoom = function (\App\Models\Team $room) {
    dd($room);
};

?>

<a href="{{ route('rooms.messages', ['room' => $room->id]) }}"
   class="relative transition hover:scale-[1.01] group drop-shadow-xl w-full overflow-hidden h-40 rounded-xl bg-[#3d3c3d]">
    <div class="absolute h-40 text-white z-[1] rounded-xl inset-0.5 bg-[#323132] w-full px-4">
        <li class="flex items-end justify-between py-4 text-white">
            <div class="flex items-center overflow-hidden">
                <div class="flex-shrink-0">
                    <img class="w-8 h-8 rounded-full" src="{{ $this->owner->profile_photo_url }}"
                         alt="{{ $this->owner->login }}">
                </div>
                <div class="flex-1 min-w-0 ms-4">
                    <p class="text-lg font-medium truncate break-all dark:text-white">
                        {{ $room->name }}
                    </p>
                    <p class="text-sm text-orange-400 text-left truncate dark:text-gray-400">
                        {{ $this->owner->id == auth()->id() ? 'Me' : $this->owner->login }}
                    </p>
                </div>
            </div>
        </li>
        <div class="flex justify-between items-end pb-2 pt-4">
            <div class="flex flex-col justify-between text-sm text-right">
                <p class="w-max">Members: <span class="text-orange-400 font-bold">{{ $room->users()->count() }}</span></p>
                <p class="w-max">Messages: <span
                        class="text-orange-400 font-bold">{{ $room->unReadMessages()->count() }}</span></p>
            </div>
            @if ($this->owner->id == auth()->id())
                <x-danger-button wire:click.prevent="deleteRoom({{ $room->id }})" class="!py-1.5">Delete room
                </x-danger-button>
            @else
                <x-danger-button wire:click.prevent="leaveRoom({{ $room->id }})">Leave</x-danger-button>
            @endif
        </div>
    </div>
    <div
        class="absolute w-64 h-40 group-hover:w-80 bg-white blur-[50px] -left-1/2 -top-1/2 group-hover:bg-orange-400 transition"></div>
</a>

