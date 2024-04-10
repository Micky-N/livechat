<?php

use function Livewire\Volt\{state, form, on};

state(['isOpen' => false, 'friends' => []]);
$form = form(\App\Livewire\Forms\RoomForm::class);

on([
    'add-friends' => function () {
        $this->isOpen = true;
    },
    'add-friend' => function (\App\Models\User $friend) {
        $this->friends[] = $friend;
    },
]);

$save = function () {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $user->personalTeam()->users()->syncWithoutDetaching(array_map(fn (\App\Models\User $friend) => $friend->id, $this->friends));
    $message = 'Friends successfully added.';
    session()->flash('success', $message);

    $this->redirect(url()->previous());
};

$removeFriend = function (int $friendId) {
    $this->friends = array_filter($this->friends, function (\App\Models\User $friend) use ($friendId) {
        return $friend->id !== $friendId;
    });
};

?>

<div>
    @if ($this->isOpen)
        <x-dialog-modal max-width="2xl" wire:model="isOpen">
            <x-slot name="title">
                {{ __('Add Friends') }}
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-x-6 sm:gap-y-2 items-start">
                    <div>
                        <label class="text-gray-600">Autocomplete
                            Friend</label>
                        <livewire:friends.components.friends-autocomplete />
                    </div>
                    <div>
                        <label for="friends"
                            class="text-gray-600">Friends</label>
                        <div
                            class="border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 min-h-16 mb-3 flex flex-wrap items-start gap-2 rounded-lg text-xs border px-2 py-2">
                            @forelse ($friends as $friend)
                                <span
                                    class="border-orange-400 bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300 inline-flex items-center rounded-md border px-2 py-1 font-medium">
                                    {{ $friend->login }}
                                    <button type="button" wire:click='removeFriend({{ $friend->id }})'
                                        class="text-orange-400 hover:bg-orange-300 hover:text-orange-900 dark:hover:bg-orange-800 dark:hover:text-orange-300 ms-2 inline-flex items-center rounded-sm bg-transparent p-1 text-sm">
                                        <svg class="h-2 w-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Remove friend</span>
                                    </button>
                                </span>
                            @empty
                                <span class="text-sm text-gray-500">empty ...</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-primary-button class="{{ $errors->any() ? 'disabled' : '' }}" wire:click="save()"
                    wire:loading.attr="disabled">
                    {{ __('Save') }}
                    <div role="status" wire:loading wire:target="save()">
                        <svg aria-hidden="true" class="w-4 h-4 ml-2 text-gray-200 animate-spin fill-orange-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                </x-primary-button>

                <x-secondary-button class="ms-3" wire:click="$toggle('isOpen')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
