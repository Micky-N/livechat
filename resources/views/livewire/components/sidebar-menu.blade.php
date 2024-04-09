<div x-data="{sidebarOpen: false}">
    <div x-on:click="sidebarOpen = false" :class="sidebarOpen ? 'fixed' : 'hidden'"
         class="dark:bg-gray-900/90 z-30 inset-0 bg-black/70 lg:hidden" id="sidebarBackdrop"></div>
    <aside class="fixed z-50 md:relative bg-orange-950/80 md:bg-transparent">
        <!-- Sidebar -->
        <input type="checkbox" class="peer hidden" x-on:click="sidebarOpen = true" id="sidebar-open"/>
        <label :class="{hidden: sidebarOpen}" x-transition
               class="absolute top-5 z-20 mx-4 cursor-pointer md:hidden text-gray-400"
               for="sidebar-open">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd" d="M13.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M19.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
            </svg>
        </label>
        <nav aria-label="Sidebar Navigation" :class="{'w-64': sidebarOpen}"
             class="left-0 z-10 text-white flex h-screen w-0 flex-col overflow-hidden transition-all md:h-screen md:w-64 lg:w-72">
            <div class="h-screen">
                <div class="flex h-full flex-grow flex-col overflow-y-auto pt-0">
                    <div class="flex mt-8 items-center px-4">
                        <img class="h-12 w-auto max-w-full align-middle rounded-full"
                             src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"/>
                        <div class="flex ml-3 flex-col">
                            <h3 class="font-medium">{{ auth()->user()->name }}</h3>
                            <p class="text-xs text-gray-400">{{ auth()->user()->login }}</p>
                        </div>
                    </div>

                    <span
                        class="ml-3 mt-10 mb-2 block text-xs font-semibold text-gray-400">Menu</span>

                    <a href="{{ route('dashboard') }}"
                       class="flex cursor-pointer items-center {{ request()->routeIs('dashboard') ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                        <svg class="mr-4 h-5 w-5 align-middle" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                  class=""></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('dm.index') }}"
                       class="flex cursor-pointer items-center {{ request()->routeIs('dm.*') ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                        <svg class="mr-4 h-5 w-5 align-middle" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Direct messages
                    </a>

                    <a href="{{ route('rooms.index') }}"
                       class="flex cursor-pointer items-center {{ request()->routeIs('rooms.*') ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                        <svg class="mr-4 h-5 w-5 align-middle" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Rooms
                    </a>

                    @if (isset($titleMenu))
                        <div class="mx-3 mt-8 block text-xs font-semibold text-gray-400">{{ $titleMenu }}</div>
                    @endif

                    @if (isset($menu))
                        <div class="flex mt-3 flex-1 flex-col overflow-hidden">
                            <div id="sidebar-list" class="overflow-auto pb-4">
                                <nav class="flex-1">
                                    {{ $menu }}
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </nav>
    </aside>
</div>
