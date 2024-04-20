<?php

use function Livewire\Volt\{state, computed, mount};

state(['room', 'notify' => false]);

$owner = computed(fn() => $this->room->owner);

$isMine = computed(fn() => $this->room->user_id == auth()->id());

$toggleNotification = function () {
    $this->notify = !$this->notify;
    $this->room->users()->updateExistingPivot(auth()->id(), [
        'notify' => $this->notify
    ]);
};

mount(function () {
    $this->notify = $this->room->users()
        ->where('user_id', auth()->id())
        ->withPivot('notify')
        ->first()
        ->membership->notify;
});

?>

<a href="{{ route('rooms.messages', ['room' => $room->id]) }}"
   class="relative transition hover:scale-[1.01] overflow-hidden group drop-shadow-xl w-full h-40 rounded-xl bg-black/40">
    <div class="absolute h-40 text-white z-[1] rounded-xl inset-0.5 bg-black/40 w-full px-4">
        <li class="flex items-end justify-between py-4 text-white">
            <div class="flex items-center overflow-hidden">
                <div class="flex-shrink-0">
                    <img class="w-10 h-10 rounded-full" src="{{ $this->owner->profile_photo_url }}"
                         alt="{{ $this->owner->login }}">
                </div>
                <div class="flex-1 min-w-0 ms-4">
                    <p class="text-lg font-medium truncate break-all text-white flex items-center">
                        @if ($this->isMine)
                            <span class="text-orange-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0 .075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 1 .198-.471 1.575 1.575 0 1 0-2.228-2.228 3.818 3.818 0 0 0-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0 1 16.35 15m.002 0h-.002"/>
                                </svg>
                            </span>
                        @endif
                        {{ $room->name }}
                    </p>
                    <p class="text-sm text-orange-400 text-left truncate">
                        {{ $this->owner->id == auth()->id() ? 'Me' : $this->owner->login }}
                    </p>
                </div>
            </div>
        </li>
        <div class="flex justify-between items-end pb-2 pt-4">
            <div class="flex flex-col justify-between text-sm text-right">
                <p class="w-max">Members: <span
                        class="text-orange-400 font-bold">{{ $room->users()->count() }}</span>
                </p>
                @if ($room->messages()->count())
                    <p class="w-max">
                        Unread messages: <span class="text-orange-400 font-bold">{{ $room->unReadMessages()->count() }}</span>
                    </p>
                @endif
            </div>
        </div>
        @if ($this->isMine)
            <button wire:click.prevent="$dispatch('edit-room', {room: {{ $room->id }}})"
                    class="bg-neutral-400/20 hover:bg-gray-200 hidden group-hover:flex justify-center items-center text-gray-200 hover:text-orange-500 p-2 rounded-bl-xl rounded-tr-xl absolute top-0 right-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path
                        d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                    <path
                        d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                </svg>
            </button>
        @else
            <button wire:click.prevent="toggleNotification()"
                    class="bg-neutral-400/20 hover:bg-gray-200 hidden group-hover:flex justify-center items-center text-gray-200 hover:text-orange-500 p-2 rounded-bl-xl rounded-tr-xl absolute top-0 right-0">
                @if ($notify)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 0 0 3.536-1.003A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5"/>
                    </svg>
                @endif
            </button>
        @endif
        <button type="button" wire:click.prevent="$dispatch('delete-room', {room: {{ $room->id }}})"
                class="absolute hidden group-hover:flex bottom-0 right-0 items-center justify-center p-2 bg-neutral-400/20 border border-transparent rounded-br-xl rounded-tl-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
            @if ($this->isMine)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                </svg>
            @endif
        </button>
    </div>
    <div
        class="absolute w-64 h-40 group-hover:w-80 bg-white/40 blur-[50px] -left-1/2 -top-1/2 group-hover:bg-orange-400/40 transition-bg duration-150">
    </div>
</a>
