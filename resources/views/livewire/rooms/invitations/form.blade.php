<?php

use function Livewire\Volt\{on, state};

state(['isOpen' => false, 'request' => null]);

on([
    'handle-request' => function (\App\Models\TeamInvitation $request) {
        $this->request = $request;
        $this->isOpen = true;
    },
]);

$handleRequest = function (string $answer) {
    if ($answer == 'accept') {
        $this->request->team->users()->syncWithoutDetaching([$this->request->user_id]);
        \App\Events\AcceptRoomInvitation::dispatch($this->request);
        $this->request->delete();
        $message = 'Request has been accepted';
    } else if ($answer == 'reject') {
        $this->request->delete();
        $message = 'Request has been rejected';
    }
    session()->flash('success', $message);

    $this->redirect(url()->previous());
};

?>

<div>
    <x-confirmation-modal wire:model="isOpen">
        <x-slot name="logo">
            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
            </div>
        </x-slot>
        <x-slot name="title">
            Handle Request from {{ $request?->user->login }}
        </x-slot>

        <x-slot name="content">
            {{ __('Do you accept the invitation for the room :room ?', ['room' => $request?->team->name]) }}
        </x-slot>

        <x-slot name="footer">
            <x-primary-button wire:click.prevent="handleRequest('accept')" class="ms-3 !py-1.5">
                {{ __('Accept') }}
            </x-primary-button>
            <x-danger-button wire:click.prevent="handleRequest('reject')" class="ms-3 !py-1.5">
                {{ __('Reject') }}
            </x-danger-button>
            <x-secondary-button wire:click="$toggle('isOpen')" class="ms-3 !py-1.5" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
        </x-slot>
    </x-confirmation-modal>
</div>
