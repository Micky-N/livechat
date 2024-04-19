<?php

use function Livewire\Volt\{state, layout, mount};

layout('layouts.app');

state(['requests' => collect()]);

mount(function () {
    /** @var \App\Models\User $user */
    $user = \Illuminate\Support\Facades\Auth::user();
    $this->requests = $user->pendingFriendsFrom;
});

?>

<div>
    <x-slot:title>
        Friend request
    </x-slot:title>
    <div class="p-6 lg:p-8">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8">
            <div class="mt-12 shadow-sm border rounded-lg overflow-x-auto">
                <table class="w-full table-auto text-sm text-left">
                    <thead class="bg-neutral-950/75 text-gray-100 font-medium border-b">
                    <tr>
                        <th class="py-3 px-6">Requester</th>
                        <th class="py-3 px-6"></th>
                    </tr>
                    </thead>
                    <tbody class="text-neutral-100 divide-y">
                    @forelse($requests as $request)
                        <tr>
                            <td
                                class="flex items-center gap-x-3 py-3 px-6 whitespace-nowrap"
                            >
                                <img src="{{ $request->profile_photo_url }}" class="w-10 h-10 rounded-full"/>
                                <div>
                                    <span class="block text-sm font-medium">
                                        {{ $request->login }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-right py-3 px-6 whitespace-nowrap">
                                <button wire:click="$dispatch('handle-request', {request: {{ $request->id }}})"
                                        class="py-1.5 px-3 bg-white text-gray-600 hover:text-gray-500 duration-150 hover:bg-gray-100 rounded-lg">
                                    Manage
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-lg py-3">
                                No friend request
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <livewire:friends.requests.form/>
</div>
