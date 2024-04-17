<?php

use function Livewire\Volt\{state, mount};
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

state(['login' => '', 'active' => false, 'users' => null, 'allUsers' => null, 'friendsIds']);

mount(function () {
    $this->allUsers = User::whereNotIn('id', $this->friendsIds)->orderBy('login')->get();
    $this->users = $this->allUsers;
});

$activelist = function (bool $status = true) {
    $this->active = $status;
};

$autocomplete = function () {
    if ($this->login) {
        $this->users = $this->allUsers->filter(function (User $user) {
            return str_starts_with(strtolower($user->login), strtolower($this->login));
        });
    } else {
        $this->users = $this->allUsers;
    }
};

$selectUser = function (User $user) {
    $this->dispatch('add-user', user: $user->id);
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
        @if ($active && $users->count())
            <div class="relative">
                <div class="top-100 absolute mt-1 w-full overflow-hidden rounded-lg border bg-white shadow-xl">
                    <div class="text-sm">
                        <ul class="divide-y max-h-72 overflow-x-hidden rounded-lg">
                            @foreach ($users as $user)
                                <li wire:click="selectUser({{ $user->id }})"
                                    class="flex w-full cursor-pointer px-3 py-2 hover:bg-gray-100">
                                    {{ $user->login }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
