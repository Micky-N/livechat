<?php

use function Livewire\Volt\{state, mount};
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

state(['login' => '', 'active' => false, 'members' => null, 'allMembers' => null]);

mount(function () {
    $this->allMembers = User::orderBy('login')->get();
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
};

?>

<div x-data="{
    login: @entangle('login'),
    active: @entangle('active')
}" x-init="$watch('login', login => $wire.autocomplete(login))">
    <div x-on:click.outside="$wire.activelist(false)">
        <input type="text" x-model="login" x-on:click="$wire.activelist(true)"
            class="block w-full min-w-0 flex-1 rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
            placeholder="Type user login" />
        @if ($active && $members->count())
            <div class="relative">
                <div class="top-100 absolute mt-1 w-full rounded-lg border bg-white shadow-xl">
                    <div class="text-sm">
                        <ul class="divide-y max-h-48 overflow-x-hidden rounded-lg">
                            @foreach ($members as $member)
                                <li wire:click="selectMember({{ $member->id }})"
                                    class="block flex w-full cursor-pointer px-3 py-2 hover:bg-gray-100">
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
