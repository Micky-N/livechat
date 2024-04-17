<?php

use function Livewire\Volt\{state, computed, on, mount};

state(['friend', 'unReadMessageCount' => 0, 'active' => false]);

on([
    'user-with.{friend.id}' => function() {
        $this->friend->unReadMessages()->where('user_id', $this->otherUser->id)->each(function (\App\Models\Message $message) {
            $message->readBy()->syncWithoutDetaching([auth()->id()]);
        });
        $this->unReadMessageCount = $this->friend->unReadMessages()->where('user_id', $this->otherUser->id)->count();
    }
]);

$otherUser = computed(fn () => optional($this->friend)->getOtherUser(auth()->user()));

mount(function () {
    $this->unReadMessageCount = $this->friend->unReadMessages()->where('user_id', $this->otherUser->id)->count();
    $this->active = request()->is('messages/friends/' . $this->friend->id);
})

?>

<a href="{{ route('friends.messages', ['friend' => $friend->id]) }}"
   class="flex cursor-pointer items-center {{ $active ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
         stroke="currentColor" class="mr-4 h-5 w-5 align-middle">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
    </svg>

    {{ $this->otherUser->login }}
    @if ($unReadMessageCount)
        <span class="ml-auto rounded-full bg-orange-500 px-2 text-xs text-white">{{ $unReadMessageCount }}</span>
    @endif
</a>
