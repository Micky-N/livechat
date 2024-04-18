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
</head>
<body class="bg-black">
<div class="font-sans text-gray-100 antialiased relative">
    <div class="-z-10 overflow-x-clip absolute inset-0 flex items-center">
        <img src="https://reverb.laravel.com/images/footer-bg.png"
             class="ml-[50%] w-full min-w-[1000px] max-w-none -translate-x-1/2 md:min-w-[auto]" aria-hidden="true">
    </div>
    {{ $slot }}
</div>

@livewireScripts
</body>
</html>
