<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <style>

        *::-webkit-scrollbar {
            width: 4px;
            height: 8px;
        }

        *::-webkit-scrollbar-thumb {
            background-color: rgb(156 163 175 / 0.75);
        }

        *::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px rgb(156 163 175 / 0.75);
        }
    </style>
</head>
<body class="font-sans antialiased bg-left md:bg-center bg-cover"
      style="background-image: url('https://reverb.laravel.com/images/hero-background.png')"
      :class="{ 'overflow-hidden': modalOpen }">
<div class="flex h-screen">
    <x-sidebar-menu>
        @if (isset($titleMenu))
            <x-slot name="titleMenu">
                {{ $titleMenu }}
            </x-slot>
        @endif
        @if (isset($menu))
            <x-slot name="menu">
                {{ $menu }}
            </x-slot>
        @endif
    </x-sidebar-menu>
    <!-- /Sidebar -->

    <div class="flex h-screen w-full flex-col justify-between overflow-hidden">
        <!-- Navbar -->
        @livewire('navigation-menu')
        <!-- /Navbar -->

        <!-- Main -->
        <main class="flex-1 overflow-auto md:rounded-tl-xl shadow bg-white/10">
            <!-- Put your content inside of the <main/> tag -->
            {{ $slot }}
        </main>
    </div>
</div>

@stack('modals')

@livewireScripts
</body>
</html>
