<?php

use function Livewire\Volt\{state, computed, on, mount};

state(['message', 'content' => '']);

$isMine = computed(fn() => $this->message->user_id == auth()->id());

$replyTo = computed(fn() => $this->message->replyTo);

on([
    'update-message.{message.id}' => function () {
        $this->message->content = trim($this->content);
        $this->message->save();
    },
    'remove-reply.{message.id}' => function () {
        $this->message->replyTo = null;
    }
]);

mount(fn() => ($this->content = $this->message->content));

?>


<div id="message-{{ $message->id }}" class="relative group hover:bg-black/40 py-0.5 px-4 rounded-md mx-4">
    @if ($this->replyTo)
        <div class="relative flex space-x-2.5 h-7 justify-between">
            <span class="absolute left-3 w-10 -top-1 text-neutral-600 flex justify-center">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    transform="matrix(-1, 0, 0, 1, 0, 0)rotate(0)">
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M20 17V15.8C20 14.1198 20 13.2798 19.673 12.638C19.3854 12.0735 18.9265 11.6146 18.362 11.327C17.7202 11 16.8802 11 15.2 11H4M4 11L8"
                            stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
            </span>
            <div class="flex-grow pl-10 text-neutral-300 font-thin text-sm flex space-x-2 items-center">
                <img class="w-4 h-4 rounded-full" src="{{ $this->replyTo->sender->profile_photo_url }}"
                    alt="{{ $this->replyTo->sender->login }}">
                <div class="flex space-x-2 items-center w-full">
                    <p class="text-orange-500 font-bold hover:underline opacity-70 hover:opacity-100 cursor-pointer">{{ $this->replyTo->sender->login }}</p>
                    <p x-on:click="scrollTo('message-{{ $this->replyTo->id }}')" class="flex-grow overflow-hidden w-2 text-nowrap text-ellipsis">
                        <span class="opacity-70 hover:opacity-100 hover:text-white cursor-pointer">{{ $this->replyTo->content }}</span>
                    </p>
                </div>
            </div>
        </div>
    @endif
    <div class="flex items-start space-x-2.5">
        <img class="w-10 h-10 mt-0.5 rounded-full" src="{{ $message->sender->profile_photo_url }}"
            alt="{{ $message->sender->login }}">
        <div class="flex flex-col space-y-1 w-full">
            <div class="flex justify-between items-end">
                <div class="flex items-end space-x-2">
                    <span class="text-lg leading-none font-semibold text-white">{{ $message->sender->login }}</span>
                    <span class="text-xs font-extralight text-gray-300">{{ $message->created_at }}</span>
                </div>
                <div x-show="messageToEdit != {{ $message->id }}"
                    class="items-center text-neutral-400 bg-slate-100/5 top-0 hidden group-hover:flex absolute right-0 rounded-bl-md rounded-tr-md overflow-hidden">
                    <button type="button" x-on:click="reply({{ $message->id }}, '{{ $message->sender->login }}')"
                        class="flex items-center justify-center w-10 h-7 hover:bg-slate-100/20 hover:text-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                    </button>
                    @if ($this->isMine)
                        <button type="button" x-on:click="messageToEdit = {{ $message->id }}"
                            class="flex items-center justify-center w-10 h-7 hover:bg-slate-100/20 hover:text-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5">
                                <path
                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                <path
                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                            </svg>
                        </button>

                        <template x-if="deleteId != {{ $message->id }}">
                            <button type="button" x-on:click="deleteId = {{ $message->id }}"
                                class="flex items-center justify-center w-10 h-7 hover:bg-slate-100/20 hover:text-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5">
                                    <path
                                        d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                    <path fill-rule="evenodd"
                                        d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.133 2.845a.75.75 0 0 1 1.06 0l1.72 1.72 1.72-1.72a.75.75 0 1 1 1.06 1.06l-1.72 1.72 1.72 1.72a.75.75 0 1 1-1.06 1.06L12 15.685l-1.72 1.72a.75.75 0 1 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>

                        <span x-show="deleteId == {{ $message->id }}" class="flex"
                            x-on:click.outside="deleteId = null">
                            <button type="button" x-on:click="deleteMessage()"
                                class="flex items-center justify-center w-10 h-7 bg-green-500/80 hover:bg-green-500 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>

                            <button type="button" x-on:click="deleteId = null"
                                class="flex items-center justify-center w-10 h-7 bg-red-500/80 hover:bg-red-500 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </span>
                    @endif
                </div>
            </div>
            <div class="text-base font-extralight text-gray-200 pr-4" x-data="{
                content: @entangle('content'),
                updateMessage(event) {
                    if (event.keyCode === 13 && !event.shiftKey) {
                        event.preventDefault();
                        if (this.content.trim()) {
                            $wire.$dispatch('update-message.{{ $message->id }}');
                            this.messageToEdit = null;
                        }
                    }
                },
            }">
                <span x-show="messageToEdit != {{ $message->id }}" x-text="content"></span>
                <form x-show="messageToEdit == {{ $message->id }}" class="py-2">
                    <label :for="$refs.chat" class="sr-only">Your message</label>
                    <div class="flex items-center px-3 rounded-lg bg-neutral-800/40">
                        <textarea x-ref="chat" rows="1" x-on:input="resize()" x-on:keydown="updateMessage($event)" x-model="content"
                            class="block resize-none p-2 w-full text-base text-gray-100 placeholder-gray-400 bg-transparent rounded-lg border-0 focus:ring-0"
                            placeholder="Your message..."></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
