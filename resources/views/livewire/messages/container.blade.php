<div class="flex h-full">
    <div class="flex-1 w-full h-full">
        <div x-data="{
            openGroup: false
        }" x-cloak class="main-body h-full w-full flex flex-col">
            <div class="main flex-1 flex flex-col">
                <div class="flex-1 flex relative">
                    @if (isset($room))
                        <div :class="openGroup ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" x-on:click.outside="openGroup = false"
                            class="sidebar transition absolute top-0 bottom-0 z-[1] lg:z-0 lg:static lg:flex w-2/4 lg:w-1/4 flex-2 flex-col py-6 pl-4 pr-10 bg-black/90 lg:bg-black/40">
                            <button type="button" x-on:click="openGroup = !openGroup"
                                class="flex justify-center w-10 h-10 hover:bg-slate-100/20 hover:text-gray-100 items-center text-neutral-400 bg-slate-100/5 top-0 lg:hidden absolute -right-10 top-0 rounded-br-md overflow-hidden">
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
                                @foreach ($room->members as $member)
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
                                    $wire.send(this.content);
                                    this.content = '';
                                }
                            }
                        },
                        scrollToBottom() {
                            const objDiv = this.$refs.messages;
                            setTimeout(() => {
                                objDiv.scrollTop = objDiv.scrollHeight;
                            }, 200)
                        }
                    }" x-cloak x-on:message-created.window="scrollToBottom()"
                        class="chat-area relative flex-grow h-full w-full overflow-hidden flex flex-col justify-between">
                        <h3 class="pt-3 text-center px-4 text-lg underline underline-offset-4 decoration-orange-500 text-white">{{ isset($room) ? $room->name : $friend->login }}</h3>
                        <div class="h-full overflow-auto relative">
                            <div x-ref="messages" id="messages" class="grow overflow-auto w-full absolute h-full">
                                <div class="overflow-auto flex-1 grid grid-rows-1 items-end gap-y-4 w-full h-full">
                                    @foreach ($messages as $message)
                                        <livewire:components.message :$message :key="$message->id" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <form class="py-4 px-4">
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

    function printSomething() {
        const element = document.querySelector('#messages')
        window.scrollTo(0, element.scrollHeight);
    }
</script>
