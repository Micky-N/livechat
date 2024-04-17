<?php

use function Livewire\Volt\{state, on, computed};

state(['isOpen' => false, 'friend' => null]);

on([
    'remove-friend' => function (\App\Models\User $friend) {
        $this->friend = $friend;
        $this->isOpen = true;
    },
]);

$removeFriend = function () {
    $id = $this->friend->id;
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $this->friend->sendedMessages()->where('recipent_id', $user->id)->where('recipent_type', \App\Models\User::class)->delete();
    $user->sendedMessages()->where('recipent_id', $this->friend->id)->where('recipent_type', \App\Models\User::class)->delete();
    $user->acceptedFriendsTo()->detach($id);
    $user->acceptedFriendsFrom()->detach($id);
    $message = 'Friend successfully removed.';
    session()->flash('success', $message);

    $this->redirect(url()->previous());
};

?>

<div >
    <x-confirmation-modal wire:model="isOpen">
        <x-slot name="title">
            Remove {{ $friend?->login }}
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
