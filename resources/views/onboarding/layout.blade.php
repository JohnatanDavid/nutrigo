<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Profil - NutriGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7edd2] px-4 py-6 text-[#17311f] sm:px-6 lg:px-8">
<div class="mx-auto flex min-h-[calc(100vh-3rem)] w-full max-w-7xl items-center justify-center">
    <div class="grid w-full overflow-hidden rounded-[30px] bg-[#fff8ea] shadow-[0_30px_90px_rgba(0,0,0,0.18)] ring-1 ring-black/5 lg:min-h-[88vh] lg:grid-cols-[0.92fr_1.08fr]">
        <aside class="flex flex-col justify-between gap-10 bg-gradient-to-br from-[#1d5b2f] via-[#195528] to-[#0e3d1f] p-8 text-white sm:p-10 lg:p-12">
            <div>
                <img src="{{ asset('assets/Logo NutriGo 2.png') }}" alt="NutriGo" class="h-14 w-auto brightness-0 invert">
                <h1 class="mt-8 max-w-md text-3xl font-extrabold leading-tight text-[#fff8ea] sm:text-4xl">
                    Lengkapi profil untuk membuka dashboard penuh
                </h1>
                <p class="mt-4 max-w-md text-sm leading-6 text-white/82 sm:text-base">
                    Data BMI sudah disimpan, lengkapi profil untuk melihat hasil rekomendasi sesuai preferensimu.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm text-white/90">
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-white/60">Langkah 1</p>
                    <p class="mt-2 font-semibold">Profil Pengguna</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-white/60">Langkah 2</p>
                    <p class="mt-2 font-semibold">Wilayah Pengguna</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-white/60">Langkah 3</p>
                    <p class="mt-2 font-semibold">Alergi Makanan</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-white/60">Langkah 4</p>
                    <p class="mt-2 font-semibold">Kebutuhan Tambahan</p>
                </div>
            </div>
        </aside>

        <section class="relative bg-[#fff3cb] p-6 sm:p-8 lg:max-h-[88vh] lg:overflow-y-auto lg:p-10">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-[#17311f] sm:text-3xl">Lengkapi profilmu</h2>
                    <p class="mt-2 max-w-xl text-sm leading-6 text-[#7a674e]">Isi data yang belum ada supaya dashboard dan rekomendasi bisa aktif penuh.</p>
                </div>
                <div class="rounded-full bg-[#fff8d6] px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] text-[#f55c1f]">Onboarding</div>
            </div>

            <div class="mb-6 flex items-center gap-3">
                <div class="flex-1 rounded-full bg-[#eadfbf] p-1">
                    <div class="grid grid-cols-5 gap-1 text-[11px] font-bold uppercase tracking-[0.2em] text-[#245432]">
                        @foreach([1,2,3,4,5] as $step)
                            <div class="rounded-full px-2 py-2 text-center {{ $currentStep >= $step ? 'bg-[#1d5b2f] text-white shadow' : 'text-[#6f6654]' }}">
                                {{ $currentStep > $step ? '✓' : $step }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <span class="shrink-0 text-xs font-bold uppercase tracking-[0.28em] text-[#7a674e]">Langkah {{ $currentStep }} / 5</span>
            </div>

            @yield('content')
        </section>
    </div>
</div>
</body>
</html>