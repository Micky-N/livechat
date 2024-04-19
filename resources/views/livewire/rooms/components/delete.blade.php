<?php

use function Livewire\Volt\{state, on, computed};

state(['isOpen' => false, 'room' => null]);

on([
    'delete-room' => function (\App\Models\Team $room) {
        $this->room = $room;
        $this->isOpen = true;
    },
]);

$isMine = computed(fn () => $this->room?->user_id === auth()->id());

$removeRoom = function () {
    if ($this->isMine) {
        $this->room->delete();
        $message = 'Room successfully deleted.';
    } else {
        auth()->user()->sendedMessages()->where('recipent_id', $this->room->id)->where('recipent_type', Team::class)->delete();
        $this->room->users()->detach(auth()->id());
        $message = 'Room successfully left.';
    }
    session()->flash('flash.banner', $message);

    $this->redirect(url()->previous());
};

?>

<div >
    <x-confirmation-modal wire:model="isOpen">
        <x-slot name="title">
            Chat Room {{ $room?->name }}
        </x-slot>

        <x-slot name="content">
            @if ($this->isMine)
                {{ __('Are you sure you would like to delete this chat room, all messages will be permanently deleted.') }}
            @else
                {{ __('Are you sure you would like to leave this chat room ?') }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('isOpen')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button wire:click.prevent="removeRoom()" class="ms-3 !py-1.5">
                {{ $this->isMine ? 'Delete' : 'Leave' }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
