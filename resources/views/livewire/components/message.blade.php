<?php

use function Livewire\Volt\{state};

state('message');

?>


<div class="flex items-start space-x-2.5">
    <img class="w-10 h-10 rounded-full" src="{{ $message->sender->profile_photo_url }}" alt="{{ $message->sender->login }}">
    <div class="flex flex-col space-y-1 w-full">
        <div class="flex items-end space-x-2">
            <span class="text-lg leading-none font-semibold text-white">{{ $message->sender->login }}</span>
            <span class="text-xs font-extralight text-gray-300">{{ $message->created_at }}</span>
        </div>
        <p class="text-base font-extralight text-gray-200">
            {{ $message->content }}
        </p>
    </div>
    <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" data-dropdown-placement="bottom-start"
            class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-white rounded-lg hover:text-gray-100 focus:ring-0 focus:outline-none"
            type="button">
        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
             fill="currentColor" viewBox="0 0 4 15">
            <path
                d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
        </svg>
    </button>
    <div id="dropdownDots"
         class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-40 dark:bg-gray-700 dark:divide-gray-600">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconButton">
            <li>
                <a href="#"
                   class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Reply</a>
            </li>
            @if ($message->user_id == auth()->id())
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</a>
                </li>
            @endif
        </ul>
    </div>
</div>

