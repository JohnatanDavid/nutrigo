<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NutriGo') - NutriGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen" x-data>

    <div class="min-h-screen flex flex-col bg-gray-50">

    {{-- Top Navigation --}}
    <header class="sticky top-0 z-50 bg-[#fff6e8]/90 backdrop-blur-md border-b border-[#f0e5d0] shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

            {{-- Left Navigation --}}
            <nav class="flex items-center gap-8">

                <a href="{{ route('user.dashboard') }}"
                class="relative font-medium transition-all duration-200
                {{ request()->routeIs('user.dashboard')
                        ? 'text-[#d94b2b]'
                        : 'text-[#6b6b6b] hover:text-[#d94b2b]' }}">

                    Dashboard

                    @if(request()->routeIs('user.dashboard'))
                        <span class="absolute -bottom-2 left-0 w-full h-0.5 bg-[#d94b2b] rounded-full"></span>
                    @endif
                </a>

                <a href="{{ route('user.history') }}"
                class="relative font-medium transition-all duration-200
                {{ request()->routeIs('user.history')
                        ? 'text-[#d94b2b]'
                        : 'text-[#6b6b6b] hover:text-[#d94b2b]' }}">

                    Riwayat

                    @if(request()->routeIs('user.history'))
                        <span class="absolute -bottom-2 left-0 w-full h-0.5 bg-[#d94b2b] rounded-full"></span>
                    @endif
                </a>

            </nav>

            {{-- Center Logo --}}
            <div class="flex items-center">
                <a href="{{ route('user.dashboard') }}" class="mx-6">
                    <img
                        src="{{ asset('assets/Logo NutriGo 2.png') }}"
                        alt="NutriGo"
                        class="h-10 hover:scale-105 transition-transform duration-200">
                </a>
            </div>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">

                <button
                    @click="open = !open"
                    class="flex items-center gap-3 px-2 py-1 rounded-xl hover:bg-white/60 transition">

                    <div class="w-10 h-10 rounded-full bg-[#f55c1f] text-white flex items-center justify-center font-semibold shadow-sm">
                        {{ strtoupper(substr(auth()->user()->nickname ?? auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="hidden sm:flex flex-col items-start leading-tight">
                        <span class="text-sm text-gray-500">
                            Halo,
                        </span>

                        <span class="font-semibold text-[#17311F]">
                            {{ auth()->user()->nickname ?? auth()->user()->name }}
                        </span>
                    </div>

                    <svg
                        class="w-4 h-4 text-gray-500 transition-transform duration-200"
                        :class="{ 'rotate-180': open }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>

                </button>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-[#f0e5d0] overflow-hidden z-50">

                    <div class="px-4 py-3 bg-[#fff6e8] border-b border-[#f0e5d0]">
                        <p class="font-semibold text-[#17311F]">
                            {{ auth()->user()->nickname ?? auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <a href="{{ route('user.profile') }}"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-[#fff6e8] transition">

                        <span>Profil Saya</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button
                            type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 transition">

                            <span>Logout</span>
                        </button>
                    </form>

                </div>

            </div>

        </div>
    </div>

    </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-4 flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-4">
                    @foreach($errors->all() as $error)
                        <p class="text-sm">⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-6 pb-6">
            @yield('content')
        </main>
    </div>
<x-flash />
</body>
</html>