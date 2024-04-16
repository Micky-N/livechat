<div class="flex h-full">
    <div class="flex-1 w-full h-full">
        <div x-data="{
            openGroup: false
        }" x-cloak class="main-body h-full w-full flex flex-col">
            <div class="main flex-1 flex flex-col">
                <div class="flex-1 flex relative">
                    @if (isset($room))
                        <div :class="openGroup ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                            x-on:click.outside="openGroup = false"
                            class="sidebar transition absolute top-0 bottom-0 z-[1] lg:z-0 lg:static lg:flex w-min lg:w-1/4 flex-2 flex-col py-6 pl-4 pr-10 bg-black/90 lg:bg-black/40">
                            <button type="button" x-on:click="openGroup = !openGroup"
                                class="flex justify-center w-10 h-10 hover:bg-slate-100/20 hover:text-gray-100 items-center text-neutral-400 bg-slate-100/5 lg:hidden absolute -right-10 top-0 rounded-br-md overflow-hidden">
                                <template x-if="!openGroup">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                </template>
                                <template x-if="openGroup">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </template>
                            </button>
                            <div class="flex-1 h-full overflow-auto px-2 space-y-8">
                                @php
                                    $members = $room->members()->whereNot('user_id', auth()->id())->get();
                                @endphp
                                @foreach ($members as $member)
                                    <div class="text-white flex items-center">
                                        <div class="flex-2">
                                            <div class="w-8 h-8 relative">
                                                <img class="w-8 h-8 rounded-full mx-auto"
                                                    src="{{ $member->profile_photo_url }}" alt="{{ $member->login }}" />
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
                    @endif
                    <div x-data="{
                        content: '',
                        deleteId: null,
                        messageToEdit: null,
                        replyTo: {
                            id: null,
                            login: ''
                        },
                        get canSend() {
                            return this.content.trim() !== '';
                        },
                        resize() {
                            const textarea = this.$refs.chat;
                            textarea.setAttribute('style', 'height:' + (textarea.scrollHeight) + 'px;overflow-y:hidden;');
                            textarea.style.height = '0';
                            textarea.style.height = (textarea.scrollHeight) + 'px';
                        },
                        send(event) {
                            if (event.keyCode === 13 && !event.shiftKey) {
                                event.preventDefault();
                                if (this.canSend) {
                                    $wire.send(this.content.trim(), this.replyTo.id);
                                    this.content = '';
                                    this.cancelReply();
                                }
                            }
                        },
                        deleteMessage() {
                            $wire.$dispatch('delete-message', { message: this.deleteId })
                            this.deleteId = null;
                        },
                        reply(id, login) {
                            this.$refs.chat.focus();
                            this.replyTo = { id, login };
                        },
                        cancelReply() {
                            this.replyTo = {
                                id: null,
                                login: ''
                            };
                        },
                        scrollTo(messageId) {
                            const message = document.getElementById(messageId);
                            message.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            message.classList.add('transition-all', 'duration-250');
                            message.classList.add('bg-orange-800/20', 'border-l-4', 'border-orange-500/20');
                            setTimeout(() => {
                                message.classList.remove('bg-orange-800/20', 'border-l-4');
                            }, 2000)

                            setTimeout(() => {
                                message.classList.remove('transition-all', 'duration-250', 'border-orange-500/20');
                            }, 4000)
                        }
                    }" x-on:keydown.escape.window="messageToEdit = null"
                        class="chat-area relative flex-grow h-full w-full flex flex-col justify-between">
                        <div class="px-4 h-10 flex items-center justify-end lg:justify-center">
                            <h3 class="text-lg border-b border-orange-500 text-white inline-flex items-center">
                                @if (isset($room))
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5 align-middle">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="mr-2 h-5 w-5 align-middle">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                    </svg>
                                @endif
                                {{ isset($room) ? $room->name : $friend->login }}
                            </h3>
                        </div>
                        <div x-ref="messages" id="messages"
                            class="overflow-auto h-96 flex-grow flex flex-col-reverse gap-y-4 w-full">
                            @foreach ($messages as $message)
                                <livewire:components.message :$message :key="$message->id" />
                            @endforeach
                        </div>
                        <form class="py-4 px-4">
                            <label :for="$refs.chat" class="sr-only">Your message</label>
                            <div x-transition x-show="replyTo.id"
                                class="py-2 px-6 text-white text-sm rounded-t-lg bg-neutral-800/40 flex justify-between items-center">
                                <p class="font-thin">Reply to <span class="text-orange-500 font-bold"
                                        x-text="replyTo.login"></span></p>
                                <button type="button" x-on:click="cancelReply"
                                    class="text-neutral-400 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-6 h-6">
                                        <path fill-rule="evenodd"
                                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div x-transition :class="replyTo.id ? 'rounded-b-lg' : 'rounded-lg'"
                                class="flex items-center px-3 py-2 bg-black/40">
                                <textarea x-ref="chat" rows="1" x-on:input="resize()" x-on:keydown="send($event)" x-model="content"
                                    class="block resize-none mx-4 p-2 w-full text-base text-gray-100 placeholder-gray-400 bg-transparent rounded-lg border-0 focus:ring-0"
                                    placeholder="Your message..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
