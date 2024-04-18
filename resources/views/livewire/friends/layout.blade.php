<?php

use function Livewire\Volt\{state};

state(['friends', 'subtitle' => '']);

?>

<div>
    @if ($subtitle)
        <x-slot:title>
            {{ $subtitle }}
        </x-slot:title>
    @endif
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
            <livewire:friends.components.friend-layout :$friend />
        @endforeach
    </x-slot:menu>
</div>
