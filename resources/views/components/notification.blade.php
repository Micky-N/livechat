<a
    x-cloak
    x-on:notify.window="openNotify"
    x-data="{
        show: false,
        data: {
            url: 'javascript:void(0)',
            profile_photo_url: '',
            login: '',
            message: ''
        },
        openNotify(event) {
            this.data = {
                url: 'javascript:void(0)',
                profile_photo_url: '',
                login: '',
                message: '',
                ...event.detail
            };
            setTimeout(() => this.show = true, 200);
            setTimeout(() => this.resetData(), 8000);
        },
        resetData() {
            this.show = false;
            setTimeout(() => {
                this.data = {
                    url: 'javascript:void(0)',
                    profile_photo_url: '',
                    login: '',
                    message: ''
                }
            }, 500);
        },
        get canPopup() {
            return this.data.url && this.data.login && this.data.message && this.data.profile_photo_url
        }
    }"
    x-show="show && canPopup"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    :href="data.url"
    class="fixed bottom-2 right-2 max-w-full min-w-72 md:min-w-md p-4 rounded-lg shadow bg-neutral-900 text-gray-300 border border-neutral-800"
    role="alert">
    <div class="flex items-center mb-3">
        <span class="mb-1 text-sm font-semibold text-white">New notification</span>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 justify-center items-center flex-shrink-0 rounded-lg focus:ring-2 p-1.5 inline-flex h-8 w-8 text-neutral-500 hover:text-white bg-neutral-800 hover:bg-neutral-700" x-on:click.prevent="resetData()" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
    <div class="flex items-center">
        <img class="w-12 h-12 rounded-full"
             :src="data.profile_photo_url" :alt="data.login"/>
        <div class="ms-3 text-sm font-normal">
            <div class="text-sm font-semibold text-white" x-text="data.login"></div>
            <div class="text-sm font-normal" x-html="data.message"></div>
            <span class="text-xs font-medium text-orange-500">a few seconds ago</span>
        </div>
    </div>
</a>
