<?php

use function Livewire\Volt\{computed, layout, mount, state};

layout('layouts.app');
state(['room' => fn(\App\Models\Team $room) => $room]);

/** @var \App\Models\User $user */
$user = \Illuminate\Support\Facades\Auth::user();

$rooms = computed(fn() => $user->allTeams());

$send = function (string $content) {
    $newMessage = $this->room->messages()->create([
        'user_id' => auth()->id(),
        'content' => $content
    ]);

    $this->room->messages->push($newMessage);

    $this->dispatch('message-created');
};

?>

<div class="h-full overflow-hidden bg-black/40">
    <livewire:rooms.layout :rooms="$this->rooms"/>
    <div class="flex h-full">
        <div class="flex-1 w-full h-full">
            <div class="main-body container h-full flex flex-col">
                <span class="md:hidden absolute inline-block ml-8 text-gray-700 hover:text-gray-900 align-bottom">
                                <span class="block h-6 w-6 p-1 rounded-full hover:bg-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                         stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                            </span>

                <div class="main flex-1 flex flex-col">
                    <div class="flex-1 flex">
                        <div class="sidebar hidden lg:flex w-1/4 flex-2 flex-col py-6 pl-4 pr-10 bg-black/20">
                            <div class="flex-1 h-full overflow-auto px-2 space-y-8">
                                @foreach($room->members as $member)
                                    <div
                                        class="text-white flex items-center">
                                        <div class="flex-2">
                                            <div class="w-8 h-8 relative">
                                                <img class="w-8 h-8 rounded-full mx-auto"
                                                     src="{{ $member->profile_photo_url }}" alt="{{ $member->login }}"/>
                                                <span
                                                    class="absolute w-4 h-4 bg-green-400 rounded-full -right-1 -bottom-1 border-2 border-white"></span>
                                            </div>
                                        </div>
                                        <div class="flex-1 px-2">
                                            <div class="truncate w-32">
                                                <span class="">{{ $member->login }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div x-data="{
                            content: '',
                            get canSend() {
                                return this.content.trim() !== '';
                            },
                            resize(){
                                const textarea = this.$refs.chat;
                                textarea.setAttribute('style', 'height:' + (textarea.scrollHeight) + 'px;overflow-y:hidden;');
                                textarea.style.height = '0';
                                textarea.style.height = (textarea.scrollHeight) + 'px';
                            },
                            send(event){
                                if (event.keyCode === 13 && !event.shiftKey) {
                                    event.preventDefault();
                                    if (this.canSend) {
                                        $wire.send(this.content);
                                        this.content = '';
                                    }
                                }
                            },
                            scrollToBottom(){
                                const objDiv = this.$refs.messages;
                                setTimeout(() => {
                                    objDiv.scrollTop = objDiv.scrollHeight;
                                }, 200)
                            }
                        }" x-cloak x-on:message-created.window="scrollToBottom()"
                            class="chat-area flex-grow h-full overflow-hidden flex flex-col justify-between pb-2">
                            <div x-ref="messages" id="messages" class="grow overflow-auto h-72">
                                <div class="overflow-auto flex-1 space-y-6 mt-4">
                                    @foreach($room->messages()->orderBy('created_at')->get() as $message)
                                        <livewire:components.message :$message :key="$message->id"/>
                                    @endforeach
                                </div>
                            </div>

                            <form class="pt-4 px-4">
                                <label :for="$refs.chat" class="sr-only">Your message</label>
                                <div class="flex items-center px-3 py-2 rounded-lg bg-black/40">
                                    <textarea x-ref="chat" rows="1" x-on:input="resize()" x-on:keydown="send($event)" x-model="content"
                                              class="block resize-none mx-4 px-2.5 py-2 w-full text-base text-gray-100 placeholder-gray-400 bg-transparent rounded-lg border-0 focus:ring-0"
                                              placeholder="Your message..."></textarea>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        setTimeout(printSomething, 1000);

        function printSomething(){
            const element = document.querySelector('#messages')
            window.scrollTo(0,element.scrollHeight);
        }
    </script>
</div>
