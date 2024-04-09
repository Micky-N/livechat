<?php

use function Livewire\Volt\{state, computed, form};

state(['room']);

$form = form(\App\Livewire\Forms\RoomForm::class);

$owner = computed(fn() => $this->room->owner);

?>

<a href="{{ route('rooms.messages', ['room' => $room->id]) }}"
    class="relative transition hover:scale-[1.01] group drop-shadow-xl w-full overflow-hidden h-40 rounded-xl bg-[#3d3c3d]">
    <button wire:click.prevent="$dispatch('edit-room', {room: {{ $room->id }}})"
        class="bg-black/40 hover:bg-gray-200 flex justify-center items-center text-gray-200 hover:text-orange-500 h-12 w-12 z-[2] rounded-xl absolute -top-3 -right-3">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="w-5 h-5 -translate-x-1 translate-y-1">
            <path
                d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
            <path
                d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
        </svg>
    </button>
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
                <p class="w-max">Members: <span class="text-orange-400 font-bold">{{ $room->users()->count() }}</span>
                </p>
                <p class="w-max">Messages: <span
                        class="text-orange-400 font-bold">{{ $room->unReadMessages()->count() }}</span></p>
            </div>
            <x-danger-button wire:click.prevent="$dispatch('delete-room', {room: {{ $room->id }}})" class="!py-1.5">
                {{ $this->owner->id == auth()->id() ? 'Delete' : 'Leave' }}
            </x-danger-button>
        </div>
    </div>
    <div
        class="absolute w-64 h-40 group-hover:w-80 bg-white blur-[50px] -left-1/2 -top-1/2 group-hover:bg-orange-400 transition">
    </div>
</a>
