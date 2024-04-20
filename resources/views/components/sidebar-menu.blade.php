@php
    $countFriendRequests = auth()->user()->pendingFriendsFrom()->count();
    $countRoomsInvitations = auth()->user()->ownedTeams()->withCount('teamInvitations')->get()->sum('team_invitations_count');
@endphp
<div x-data="{ sidebarOpen: false }">
    <div x-on:click="sidebarOpen = false" :class="sidebarOpen ? 'fixed' : 'hidden'"
         class="z-30 inset-0 bg-neutral-950/70 lg:hidden" id="sidebarBackdrop"></div>
    <aside x-cloak class="fixed md:relative transition"
           :class="sidebarOpen ? 'translate-x-0 z-50' : '-translate-x-full md:translate-x-0'">
        <!-- Sidebar -->
        <button type="button" x-on:click="sidebarOpen = !sidebarOpen"
                class="flex justify-center w-10 h-10 hover:bg-white/40 hover:text-neutral-100 items-center text-neutral-400 bg-white/20 md:hidden absolute -right-10 top-0 rounded-br-md overflow-hidden">
            <template x-if="!sidebarOpen">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"/>
                </svg>
            </template>
            <template x-if="sidebarOpen">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </template>
        </button>
        <nav aria-label="Sidebar Navigation"
             class="bg-orange-950/90 md:bg-transparent left-0 z-10 text-white flex h-screen flex-col overflow-hidden transition-all md:h-screen w-72">
            <div class="h-screen">
                <div class="flex h-full flex-grow flex-col overflow-y-auto pt-0">
                    <div class="flex flex-col mt-8 items-center px-4">
                        <img class="h-16 w-auto max-w-full align-middle rounded-full"
                             src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"/>
                        <h3 class="font-medium mt-2">{{ auth()->user()->name }}</h3>
                        <p class="text-xs text-neutral-400 mt-1">{{ auth()->user()->login }}</p>
                    </div>

                    <span class="ml-3 mt-8 mb-2 block text-xs font-semibold text-neutral-400">Menu</span>

                    <a href="{{ route('rooms.index') }}"
                       class="flex cursor-pointer items-center {{ request()->routeIs('rooms.*') ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                        <svg class="mr-4 h-5 w-5 align-middle" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                  class=""></path>
                        </svg>
                        Rooms
                    </a>

                    <a href="{{ route('rooms.invitations.index') }}"
                       class="flex cursor-pointer items-center {{ request()->routeIs('rooms.invitations.*') ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-8 pr-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="mr-4 h-5 w-5 align-middle">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                        </svg>
                        Room invitations
                        @if ($countRoomsInvitations)
                            <span
                                class="ml-auto rounded-full bg-orange-500 px-2 text-xs text-white">{{ $countRoomsInvitations }}</span>
                        @endif
                    </a>

                    <a href="{{ route('friends.index') }}"
                       class="flex cursor-pointer items-center {{ request()->routeIs('friends.*') ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="mr-4 h-5 w-5 align-middle">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                        Friends
                    </a>

                    <a href="{{ route('friends.requests.index') }}"
                       class="flex cursor-pointer items-center {{ request()->routeIs('friends.requests.*') ? 'border-l-4 text-orange-400' : 'text-white' }} border-l-orange-400 py-3 px-8 pr-4 text-sm font-medium outline-none transition-all duration-100 ease-in-out hover:border-l-4 hover:border-l-orange-400 hover:text-orange-400 focus:border-l-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="mr-4 h-5 w-5 align-middle">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                        </svg>
                        Friend requests
                        @if ($countFriendRequests)
                            <span
                                class="ml-auto rounded-full bg-orange-500 px-2 text-xs text-white">{{ $countFriendRequests }}</span>
                        @endif
                    </a>

                    @if (isset($titleMenu))
                        <div class="mx-3 mt-6 block text-xs font-semibold text-neutral-400">{{ $titleMenu }}</div>
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
