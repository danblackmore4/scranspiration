<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SCRANSPIRATION') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen bg-white">
    <div class="min-h-screen">

        {{-- =======================
           HEADER NAVIGATION
        ======================== --}}
        <header class="shadow bg-white">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center">

                {{-- LEFT — empty to center logo --}}
                <div class="flex-1"></div>

                {{-- CENTER — Title --}}
                <div class="flex-1 flex justify-center">
                    <a href="{{ route('recipes.index') }}" class="text-2xl font-bold tracking-wide text-black">
                        SCRANSPIRATION
                    </a>
                </div>

                {{-- RIGHT — Create + username/login --}}
                <div class="flex-1 flex items-center">

                    {{-- Middle-right: CREATE --}}
                    <div class="flex-1 flex justify-center">
                        @auth
                            <a href="{{ route('recipes.create') }}"
                               class="font-semibold text-black hover:text-gray-700">
                                Create +
                            </a>
                        @endauth
                    </div>

                    {{-- Far-right: username / login --}}
                    <div class="flex items-center justify-end ml-auto">

                        @auth
                            <a href="{{ route('user.profile', Auth::user()) }}"
                               class="text-black font-medium underline">
                                {{ Auth::user()->name }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-black hover:text-gray-700">
                                Login
                            </a>
                        @endauth

                    </div>

                </div>

            </div>

            {{-- SEARCH BAR --}}
            <div class="max-w-4xl mx-auto px-4 pb-4">
                <form action="{{ route('recipes.search') }}" method="GET">
                    <input type="text"
                           name="q"
                           placeholder="Search recipes..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
                </form>
            </div>

            @auth
                @foreach (auth()->user()->unreadNotifications as $n)
                    <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-2 rounded mb-2">
                        <strong>{{ $n->data['commenter'] }}</strong>
                        commented:
                        "{{ $n->data['body'] }}"
                        on
                        <a href="{{ route('recipes.show', $n->data['recipe_id']) }}" class="underline">
                            {{ $n->data['recipe_title'] }}
                        </a>

                        <form method="POST" action="{{ route('notifications.read', $n->id) }}" class="inline">
                            @csrf
                            <button class="text-sm text-blue-600 underline ml-2">Mark read</button>
                        </form>
                    </div>
                @endforeach
            @endauth


            
        </header>

        {{-- MAIN CONTENT --}}
        <main class="pt-4 bg-white min-h-screen">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
