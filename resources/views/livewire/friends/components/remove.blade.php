<?php

use function Livewire\Volt\{state, on, computed};

state(['isOpen' => false, 'friend' => null]);

on([
    'remove-friend' => function (\App\Models\Friend $friend) {
        $this->friend = $friend;
        $this->isOpen = true;
    },
]);

$user = computed(fn () => optional($this->friend)->getOtherUser(auth()->user()));

$removeFriend = function () {
    $id = $this->friend->id;
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $this->friend->messages()->delete();
    $this->friend->delete();
    $message = 'Friend successfully removed.';
    session()->flash('success', $message);

    $this->redirect(url()->previous());
};

?>

<div >
    <x-confirmation-modal wire:model="isOpen">
        <x-slot name="title">
            Remove {{ $this->user?->login }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove your friend ?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('isOpen')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button wire:click.prevent="removeFriend()" class="ms-3 !py-1.5">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
