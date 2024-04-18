<?php

use App\Models\User;
use function Livewire\Volt\{mount, state};

state(['login' => '', 'active' => false, 'members' => null, 'allMembers' => null, 'usersIds']);

mount(function () {
    $this->allMembers = User::whereNotIn('id', $this->usersIds)->orderBy('login')->get();
    $this->members = $this->allMembers;
});

$activelist = function (bool $status = true) {
    $this->active = $status;
};

$autocomplete = function () {
    if ($this->login) {
        $this->members = $this->allMembers->filter(function (User $member) {
            return str_starts_with(strtolower($member->login), strtolower($this->login));
        });
    } else {
        $this->members = $this->allMembers;
    }
};

$selectMember = function (User $member) {
    $this->dispatch('add-member', member: $member->id);
    $this->active = false;
};

?>

<div x-data="{
    login: @entangle('login'),
    active: @entangle('active')
}" x-init="$watch('login', login => $wire.autocomplete(login))">
    <div x-on:click.outside="$wire.activelist(false)">
        <x-input type="text" x-model="login" x-on:click="$wire.activelist(true)"
                 class="w-full" placeholder="Type user login"/>
        @if ($active && $members->count())
            <div class="relative">
                <div class="top-100 absolute mt-1 w-full rounded-lg overflow-hidden border border-neutral-600 bg-neutral-800 shadow-xl">
                    <div class="text-sm">
                        <ul class="divide-y divide-neutral-600 max-h-72 overflow-x-hidden rounded-lg">
                            @foreach ($members as $member)
                                <li wire:click="selectMember({{ $member->id }})"
                                    class="flex w-full cursor-pointer px-3 py-2 hover:bg-neutral-600">
                                    {{ $member->login }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
