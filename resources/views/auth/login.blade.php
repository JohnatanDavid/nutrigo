<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NutriGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7edd2]">
    <div class="grid min-h-screen lg:grid-cols-[1.05fr_0.95fr]">
        <section class="relative flex items-center overflow-hidden bg-[linear-gradient(180deg,#fff7df,#ffe07d)] px-6 py-10 lg:px-12">
            <div class="absolute inset-y-0 right-0 w-2/5 bg-[rgba(255,160,0,0.18)]"></div>
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(255,255,255,0.8),rgba(255,255,255,0))]"></div>
            <div class="relative z-10 max-w-xl">
                <img src="{{ asset('assets/Logo NutriGo 2.png') }}" alt="NutriGo" class="h-14 w-auto">
                <h1 class="mt-8 text-4xl font-black leading-tight text-[#17311f] sm:text-5xl">Selamat Datang!</h1>
                <p class="mt-3 max-w-lg text-base font-medium text-[#4f4637]">Silakan masuk ke akun NutriGo untuk melihat dashboard, menyimpan data kesehatan, dan menerima rekomendasi menu yang sudah dipersonalisasi.</p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <img src="{{ asset('assets/Group 4.png') }}" alt="Menu 1" class="h-48 w-full rounded-[26px] object-cover shadow-[0_18px_40px_rgba(0,0,0,0.18)]">
                    <div class="grid gap-4">
                        <img src="{{ asset('assets/Group 5.png') }}" alt="Menu 2" class="h-20 w-full rounded-[22px] object-cover shadow-[0_16px_34px_rgba(0,0,0,0.16)]">
                        <img src="{{ asset('assets/Mask group.png') }}" alt="Menu 3" class="h-20 w-full rounded-[22px] object-cover shadow-[0_16px_34px_rgba(0,0,0,0.16)]">
                    </div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center bg-[#fff6ea] px-6 py-10 lg:px-12">
            <div class="w-full max-w-lg rounded-[30px] bg-white p-8 shadow-[0_20px_60px_rgba(61,42,10,0.12)] ring-1 ring-black/5">
                <p class="text-sm font-bold uppercase tracking-[0.28em] text-[#f55c1f]">Login</p>
                <h2 class="mt-3 text-3xl font-black text-[#17311f]">Masuk ke akun NutriGo</h2>
                <p class="mt-2 text-sm text-[#6c6352]">Lanjutkan dari dashboard publik, lalu simpan data kesehatanmu ke database.</p>

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#4f4637]">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field !rounded-full !border-[#c8b98d] !bg-[#fff9ef]" placeholder="nama@email.com" required>
                        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#4f4637]">Password</label>
                        <input type="password" name="password" class="input-field !rounded-full !border-[#c8b98d] !bg-[#fff9ef]" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="mt-2 w-full rounded-full bg-gradient-to-r from-[#8dbc00] to-[#1d5b2f] px-5 py-3.5 text-sm font-extrabold uppercase tracking-[0.22em] text-white shadow-[0_16px_30px_rgba(29,91,47,0.28)]">Masuk →</button>
                </form>

                <div class="my-6 flex items-center gap-4 text-sm text-[#7a715f]"><span class="h-px flex-1 bg-[#eadfbe]"></span><span>atau</span><span class="h-px flex-1 bg-[#eadfbe]"></span></div>

                <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-full bg-[#f55c1f] px-5 py-3.5 text-sm font-extrabold uppercase tracking-[0.22em] text-white shadow-[0_16px_30px_rgba(245,92,31,0.26)]">Daftar</a>

                <p class="mt-6 text-center text-sm text-[#6c6352]">
                    Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-[#245432] underline decoration-[#245432]/40 underline-offset-4">Buat akun</a>
                </p>
            </div>
        </section>
    </div>
</body>
</html>