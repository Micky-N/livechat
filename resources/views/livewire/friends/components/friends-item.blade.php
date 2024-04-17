<a href="{{ route('friends.messages', ['friend' => $friend->id]) }}"
    class="relative transition hover:scale-[1.01] group drop-shadow-xl w-full overflow-hidden py-4 rounded-xl bg-black/60 flex flex-col justify-center items-center">
    <button type="button" wire:click.prevent="$dispatch('remove-friend', {friend: {{ $friend->id }}})"
        class="absolute hidden group-hover:flex top-0 right-0 items-center justify-center px-2 py-2 bg-neutral-400/20 border border-transparent rounded-bl-xl rounded-tr-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
        </svg>
    </button>
    <img src="{{ $user->profile_photo_url }}" class="w-24 h-24 rounded-full" alt="{{ $user->login }}" />
    <h4 class="text-white font-semibold sm:text-lg mt-4">{{ $user->login }}</h4>
    <p class="text-sm text-orange-400 truncate">
        {{ $user->email }}
    </p>
    <div
        class="absolute w-64 h-40 group-hover:w-80 bg-white/40 blur-[50px] -left-1/2 -top-1/2 group-hover:bg-orange-400/40 transition-bg duration-75">
    </div>
</a>
