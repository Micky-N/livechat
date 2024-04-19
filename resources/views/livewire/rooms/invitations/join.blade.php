<?php

use function Livewire\Volt\{computed, mount, on, state};

state(['isOpen' => false, 'name' => '', 'active' => false, 'rooms' => null]);

on([
    'join-room' => function () {
        $this->isOpen = true;
    }
]);

$room = computed(fn () => \App\Models\Team::whereName($this->name)->first());

$allRooms = computed(fn () => \App\Models\Team::whereRelation(
    'users', 'users.id', '!=', auth()->id()
)->get()->load('owner'));

$save = function () {
    $teamInvitation = $this->room->teamInvitations()->create([
        'user_id' => auth()->id()
    ]);
    \App\Events\SendRoomInvitation::dispatch($teamInvitation);
    session()->flash('success', 'Invitation successfully sent');

    $this->redirect(url()->previous());
};

$activelist = function (bool $status = true) {
    $this->active = $status;
};

$autocomplete = function () {
    if ($this->name) {
        $this->rooms = $this->allRooms->filter(function (\App\Models\Team $room) {
            return str_starts_with(strtolower($room->name), strtolower($this->name));
        });
    } else {
        $this->rooms = $this->allRooms;
    }
};

$selectRoom = function (\App\Models\Team $room) {
    $this->name = $room->name;
    $this->active = false;
};

mount(function () {
    $this->rooms = $this->allRooms;
});

?>

<div>
    @if ($this->isOpen)
        <x-dialog-modal max-width="2xl" wire:model="isOpen">
            <x-slot name="title">
                Join Room
            </x-slot>

            <x-slot name="content">
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-x-6 sm:gap-y-2">
                    <div class="col-span-2">
                        <x-label for="name">
                            Enter the room's name
                        </x-label>
                        <div x-data="{
                            name: @entangle('name'),
                            active: @entangle('active'),
                        }" x-init="$watch('name', name => $wire.autocomplete(name))">
                            <div x-on:click.outside="$wire.activelist(false)">
                                <x-input type="text" x-model="name" x-on:click="$wire.activelist(true)"
                                         class="w-full" placeholder="Type room name" autofocus="no"/>
                                @if ($active && $rooms->count())
                                    <div class="relative">
                                        <div class="top-100 absolute mt-1 w-full rounded-lg overflow-hidden border border-neutral-600 bg-neutral-800 shadow-xl">
                                            <div class="text-sm">
                                                <ul class="divide-y divide-neutral-600 max-h-72 overflow-x-hidden rounded-lg">
                                                    @foreach ($rooms->sortBy('name') as $room)
                                                        <li wire:click="selectRoom({{ $room->id }})"
                                                            class="flex flex-col items-start w-full cursor-pointer px-3 py-2 hover:bg-neutral-600">
                                                            <span class="text-neutral-100">{{ $room->name }}</span>
                                                            <span class="mt-2 italic text-xs">{{ $room->owner->login }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex items-center justify-end w-full">
                    <div>
                        <x-primary-button class="{{ $errors->any() ? 'disabled' : '' }}" wire:click="save()"
                                          wire:loading.attr="disabled">
                            {{ __('Save') }}
                            <div role="status" wire:loading wire:target="save()">
                                <svg aria-hidden="true" class="w-4 h-4 ml-2 text-neutral-200 animate-spin fill-orange-600"
                                     viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                        fill="currentColor"/>
                                    <path
                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                        fill="currentFill"/>
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </x-primary-button>

                        <x-secondary-button class="ms-3" wire:click="$toggle('isOpen')" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                    </div>
                </div>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
