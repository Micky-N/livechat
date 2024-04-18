<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-neutral-200 border border-transparent rounded-md font-semibold text-xs text-neutral-800 uppercase tracking-widest hover:bg-white focus:bg-white active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
