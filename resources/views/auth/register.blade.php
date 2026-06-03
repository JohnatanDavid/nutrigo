<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - NutriGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7edd2]">
    <div class="grid min-h-screen lg:grid-cols-[0.98fr_1.02fr]">
        <section class="order-2 flex items-center justify-center bg-[#fff6ea] px-6 py-10 lg:order-1 lg:px-12">
            <div class="w-full max-w-lg rounded-[30px] bg-white p-8 shadow-[0_20px_60px_rgba(61,42,10,0.12)] ring-1 ring-black/5">
                <p class="text-sm font-bold uppercase tracking-[0.28em] text-[#f55c1f]">Register</p>
                <h1 class="mt-3 text-3xl font-black text-[#17311f]">Buat Akun NutriGo</h1>
                <p class="mt-2 text-sm text-[#6c6352]">Daftar untuk menyimpan data kesehatan, alergi, dan rekomendasi menu pribadi.</p>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#4f4637]">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input-field !rounded-full !border-[#c8b98d] !bg-[#fff9ef]" placeholder="Nama kamu" required>
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#4f4637]">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field !rounded-full !border-[#c8b98d] !bg-[#fff9ef]" placeholder="nama@email.com" required>
                        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#4f4637]">Password</label>
                        <input type="password" name="password" class="input-field !rounded-full !border-[#c8b98d] !bg-[#fff9ef]" placeholder="Min. 8 karakter" required>
                        @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#4f4637]">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="input-field !rounded-full !border-[#c8b98d] !bg-[#fff9ef]" placeholder="Ulangi password" required>
                    </div>
                    <button type="submit" class="mt-2 w-full rounded-full bg-gradient-to-r from-[#ff7a1f] to-[#f24c16] px-5 py-3.5 text-sm font-extrabold uppercase tracking-[0.22em] text-white shadow-[0_16px_30px_rgba(242,76,22,0.26)]">Daftar →</button>
                </form>

                <p class="mt-6 text-center text-sm text-[#6c6352]">
                    Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-[#245432] underline decoration-[#245432]/40 underline-offset-4">Masuk</a>
                </p>
            </div>
        </section>

        <section class="order-1 relative flex items-center overflow-hidden bg-[linear-gradient(180deg,#fff7df,#ffe07d)] px-6 py-10 lg:order-2 lg:px-12">
            <div class="absolute inset-y-0 left-0 w-2/5 bg-[rgba(255,160,0,0.18)]"></div>
            <div class="absolute -right-24 top-10 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(255,255,255,0.8),rgba(255,255,255,0))]"></div>
            <div class="relative z-10 max-w-xl">
                <img src="{{ asset('assets/Logo NutriGo 2.png') }}" alt="NutriGo" class="h-14 w-auto">
                <h2 class="mt-8 text-4xl font-black leading-tight text-[#17311f] sm:text-5xl">Mulai hidup sehat dari sini.</h2>
                <p class="mt-3 max-w-lg text-base font-medium text-[#4f4637]">Setelah akun aktif, data BMI dan kebutuhan kalori akan tersimpan di dashboard kamu.</p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <img src="{{ asset('assets/Group 5.png') }}" alt="Menu 1" class="h-48 w-full rounded-[26px] object-cover shadow-[0_18px_40px_rgba(0,0,0,0.18)]">
                    <div class="grid gap-4">
                        <img src="{{ asset('assets/Mask group.png') }}" alt="Menu 2" class="h-20 w-full rounded-[22px] object-cover shadow-[0_16px_34px_rgba(0,0,0,0.16)]">
                        <img src="{{ asset('assets/Group 4.png') }}" alt="Menu 3" class="h-20 w-full rounded-[22px] object-cover shadow-[0_16px_34px_rgba(0,0,0,0.16)]">
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>