<?php

use function Livewire\Volt\{computed, form, mount, on, state};

state(['isOpen' => false, 'notify' => false]);
form(\App\Livewire\Forms\RoomForm::class);

on([
    'add-room' => function () {
        $this->form->reset();
        $this->notify = false;
        $this->isOpen = true;
    },
    'edit-room' => function (\App\Models\Team $room) {
        $this->form->reset();
        $this->form->setRoom($room);
        $this->notify = $this->form->room->users()
            ->where('user_id', auth()->id())
            ->withPivot('notify')
            ->first()
            ->membership->notify;
        $this->isOpen = true;
    },
    'add-member' => function (\App\Models\User $member) {
        $this->form->members[] = $member;
    },
]);

$save = function () {
    if ($this->form->room) {
        $this->form->update();
        $this->form->room->users()->updateExistingPivot(auth()->id(), [
            'notify' => $this->notify
        ]);
        $message = 'Room successfully updated.';
    } else {
        $this->form->store();
        $message = 'Room successfully store.';
    }
    session()->flash('success', $message);

    $this->redirect(url()->previous());
};

$usersIds = computed(fn() => optional($this->form->room)->allUsers()->map(fn($user) => $user->id));

$removeMember = function (int $memberId) {
    $this->form->members = array_filter($this->form->members, function (\App\Models\User $formMember) use ($memberId) {
        return $formMember->id !== $memberId;
    });
};

?>

<div>
    @if ($this->isOpen)
        <x-dialog-modal max-width="2xl" wire:model="isOpen">
            <x-slot name="title">
                {{ $this->form->room ? 'Edit Room ' . $this->form->room->name : 'New Room' }}
            </x-slot>

            <x-slot name="content">
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-x-6 sm:gap-y-2">
                    <div class="col-span-2">
                        <x-label for="name">
                            Enter the room's name
                        </x-label>
                        <x-input type="text" id="name" wire:model.blur="form.name" class="w-full" />
                        @error('form.name')
                        <span class="text-red-500 text-sm font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($this->form->room)
                        <div>
                            <label class="text-neutral-300">Autocomplete
                                Member</label>
                            <livewire:rooms.components.members-autocomplete :users-ids="$this->usersIds"/>
                        </div>
                        <div>
                            <label for="members"
                                   class="text-neutral-300">Members</label>
                            <div
                                class="border-neutral-600 bg-neutral-900 min-h-16 mb-3 flex flex-wrap items-start gap-2 rounded-lg text-xs border px-2 py-2">
                                @forelse ($form->members as $member)
                                    <span
                                        class="border-orange-400 bg-orange-900 text-orange-300 inline-flex items-center rounded-md border px-2 py-1 font-medium">
                                        {{ $member->login }}
                                        <button type="button" wire:click='removeMember({{ $member->id }})'
                                                class="text-orange-400 hover:bg-orange-800 hover:text-orange-300 ms-2 inline-flex items-center rounded-sm bg-transparent p-1 text-sm">
                                            <svg class="h-2 w-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                 fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round" stroke-width="2"
                                                      d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Remove member</span>
                                        </button>
                                    </span>
                                @empty
                                    <span class="text-sm text-neutral-500">empty ...</span>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex items-center @if ($form->room)justify-between @else justify-end @endif w-full">
                    @if ($form->room)
                        <div class="flex items-center justify-start">
                            <label for="silent" class="text-sm font-medium text-neutral-100">Active notification for this
                                room ?</label>
                            <x-checkbox id="silent" type="checkbox" wire:model="notify" class="ms-2" />
                        </div>
                    @endif
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
