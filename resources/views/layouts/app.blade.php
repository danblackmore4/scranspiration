<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        class="font-sans antialiased min-h-screen"
        style="background: white;"
    >
        <div class="min-h-screen">

            {{-- HEADER --}}
            <header class="shadow">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center">

                    {{-- Left spacer --}}
                    <div class="flex-1"></div>

                    {{-- Centered Title --}}
                    <div class="flex-1 flex justify-center">
                        <a href="{{ route('recipes.index') }}" class="text-2xl font-bold tracking-wide text-black">
                            SCRANSPIRATION
                        </a>
                    </div>

                    {{-- Right side split into two zones --}}
                    <div class="flex-1 flex items-center">

                        {{-- Middle-right: Create link --}}
                        <div class="flex-1 flex justify-center">
                            @auth
                                <a href="{{ route('recipes.create') }}"
                                   class="font-semibold text-black-100 hover:gray">
                                    Create +
                                </a>
                            @endauth
                        </div>

                        {{-- Far-right: username / login --}}
                        <div class="flex items-center justify-end ml-auto">
                            @auth
                                <span class="text-black-100 font-medium">
                                    {{ Auth::user()->name }}
                                </span>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}" class="text-black-100 hover:text-gray">
                                    Login
                                </a>
                            @endguest
                        </div>

                    </div>

                </div>
            </header>

            <!-- Page Content -->
            <main class="pt-4 bg-white min-h-screen">
                {{ $slot }}
            </main>

        </div>
    </body>
</html>
