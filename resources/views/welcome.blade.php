<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
</head>
<body class="leading-normal tracking-normal text-white bg-cover bg-fixed relative"
      style="background-image: url('https://static.vecteezy.com/system/resources/previews/005/869/731/non_2x/dark-orange-colorful-blur-background-vector.jpg');">
<div class="fixed -z-10 bg-black/60 w-full h-full top-0 left-0"></div>
<div class="h-full m-6">
    <!--Nav-->
    <div class="w-full container mx-auto">
        <div class="w-full flex items-center justify-between">
            <x-application-logo class="w-96"/>

            <div class="flex w-1/2 justify-end content-center">
                @if (Route::has('login'))
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @auth
                            <a
                                href="{{ url('/rooms') }}"
                                class="rounded-md px-3 py-2 ring-1 ring-transparent transition focus:outline-none text-white hover:text-white/80 focus:ring-orange-700"
                            >
                                Rooms
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 ring-1 ring-transparent transition focus:outline-none text-white hover:text-white/80 focus:ring-orange-700"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="rounded-md px-3 py-2 ring-1 ring-transparent transition focus:outline-none text-white hover:text-white/80 focus:ring-orange-700"
                                >
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <!--Main-->
    <div class="container pt-24 mx-auto flex flex-wrap flex-col md:flex-row items-center">
        <!--Left Col-->
        <div class="flex flex-col w-full xl:w-2/5 justify-center lg:items-start overflow-y-hidden">
            <h1 class="my-4 text-3xl md:text-9xl opacity-75 font-bold leading-tight text-center md:text-left bg-clip-text text-transparent bg-gradient-to-r from-orange-200 via-orange-400 to-orange-500">
                LiveChat
            </h1>
            <p class="leading-normal text-base md:text-2xl mb-8 text-center md:text-left">
                Connect with friends in real-time with our dynamic chat room platform, where you can effortlessly engage in lively conversations, and stay connected anytime, anywhere.
            </p>
        </div>

        <!--Right Col-->
        <div class="w-full relative xl:w-3/5 p-12 overflow-hidden">
            <div class="group">
                <img alt="mockup"
                     class="mx-auto w-full md:w-4/5 transform -rotate-6 transition group-hover:scale-105 duration-700 ease-in-out group-hover:rotate-6"
                     src="{{ asset('mockup.png') }}"/>
                <a href="{{ route('register') }}" class="opacity-0 group-hover:opacity-100 group-hover:rotate-0 transform -rotate-6 transition-[opacity,transform] duration-700 text-xl sm:text-3xl font-bold px-6 py-3 rounded-xl bg-orange-700/60 hover:bg-orange-500 text-white absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2">Join Now</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
