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

        {{-- Top Navigation (mockup style) --}}
        <header class="bg-[#fff6e8] border-b border-[#f0e5d0]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <nav class="flex items-center gap-6">
                        <a href="{{ route('user.dashboard') }}" class="text-[#d94b2b] font-bold">Dashboard</a>
                        <a href="{{ route('user.history') }}" class="text-[#6b6b6b]">Riwayat</a>
                    </nav>

                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="mx-6">
                            <img src="{{ asset('assets/Logo NutriGo 2.png') }}" alt="NutriGo" class="h-10">
                        </a>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('user.notifications') }}" class="relative text-xl">🔔
                            @if(auth()->user()->notifications()->where('is_read',false)->count() > 0)
                                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ auth()->user()->notifications()->where('is_read',false)->count() }}</span>
                            @endif
                        </a>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('user.profile') }}" class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center border-2 border-[#f55c1f]">
                                    {{ strtoupper(substr(auth()->user()->nickname ?? auth()->user()->name, 0, 1)) }}
                                </div>
                            </a>
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