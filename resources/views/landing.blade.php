<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriGo - Temukan Menu Sehat Harianmu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<div class="flex flex-col bg-white">
    <div class="self-stretch bg-white pb-3">

        {{-- NAVBAR --}}
        <nav class="bg-[#F3E8CC] shadow-sm sticky top-0 z-50">

            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

                <img src="{{ asset('assets/Logo NutriGo.png') }}" class="h-12">

                <div class="hidden md:flex items-center gap-8">

                    <a href="#home" class="font-bold text-[#18542A] transition-all duration-300 ease-in-out hover:text-[#D52518]">
                        Home
                    </a>

                    <a href="#fitur" class="font-bold text-[#18542A] transition-all duration-300 ease-in-out hover:text-[#D52518]">
                        Fitur
                    </a>

                    <a href="#cara-kerja" class="font-bold text-[#18542A] transition-all duration-300 ease-in-out hover:text-[#D52518]">
                        Cara Kerja
                    </a>

                    <a href="#artikel" class="font-bold text-[#18542A] transition-all duration-300 ease-in-out hover:text-[#D52518]">
                        Artikel
                    </a>

                </div>

            </div>

        </nav>

        {{-- HERO SECTION --}}
        <section id="home" class="relative overflow-hidden bg-[#F3E8CC] py-16 lg:py-24">

            {{-- Background Blob --}}
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-[#FFC926]/60 blur-3xl rounded-full"></div>
            <div class="absolute top-40 right-0 w-96 h-96 bg-[#F96015]/40 blur-3xl rounded-full"></div>

            <div class="max-w-7xl mx-auto px-6">

                <div class="grid lg:grid-cols-2 gap-12 items-center">

                    {{-- KIRI --}}
                    <div>

                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#18542A] leading-tight">
                            Sehat Itu Seru & Nikmat!
                        </h1>

                        <p class="mt-6 text-lg text-[#4E4633] leading-relaxed max-w-xl">
                            Ubah gaya hidupmu menjadi petualangan rasa yang penuh warna.
                            Tracking kalori dan rekomendasi menu nusantara yang pas buat lidah Gen Z.
                        </p>

                        <div class="flex flex-wrap gap-4 mt-8">

                            <a href="{{ route('user.dashboard') }}"
                                class="bg-[#D52518] text-white px-8 py-4 rounded-full font-bold shadow-lg hover:scale-105 transition">
                                Mulai Sekarang
                            </a>

                            <a href="#"
                                class="border-2 border-[#18542A] text-[#18542A] px-8 py-4 rounded-full font-bold hover:bg-[#18542A] hover:text-white transition">
                                Pelajari Lebih Lanjut
                            </a>

                        </div>

                    </div>

                    {{-- KANAN --}}
                    <div class="relative">

                        <div class="flex flex-col shrink-0 items-center relative">
                            <button
                                class="flex flex-col items-start bg-[#FFDF954D] text-left p-[31px] rounded-tl-[281px] rounded-tr-[93px] rounded-br-[304px] rounded-bl-[164px] border-0"
                                onclick="alert('Pressed!')"}>
                                <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/7c69158d-13e1-4bf6-b781-e6879355c568"
                                    class="w-[404px] h-[404px] rounded-tl-[281px] rounded-tr-[93px] rounded-br-[304px] rounded-bl-[164px] object-fill" />
                            </button>
                            <button
                                class="flex flex-col items-start bg-white text-left absolute top-[-17px] right-[-16px] py-4 px-[15px] rounded-[32px] border-0"
                                style="box-shadow: 0px 20px 40px #765A001C" onclick="alert('Pressed!')"}>
                                <div class="flex items-center gap-3">
                                    <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/30323522-9f74-4450-86a8-7692c322b467"
                                        class="w-4 h-[19px] rounded-[32px] object-fill" />
                                    <div class="flex flex-col shrink-0 items-center py-1">
                                        <span class="text-[#201B11] text-base font-bold">
                                            2000 kcal/hari
                                        </span>
                                    </div>
                                </div>
                            </button>
                            <button
                                class="flex flex-col items-start bg-white text-left absolute bottom-[178px] left-[-48px] py-[17px] px-[15px] rounded-[32px] border-0"
                                style="box-shadow: 0px 20px 40px #765A001C" onclick="alert('Pressed!')"}>
                                <div class="flex items-center gap-[13px]">
                                    <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/1e82e580-7d52-4016-81b0-573dae9d799f"
                                        class="w-[15px] h-5 rounded-[32px] object-fill" />
                                    <span class="text-[#201B11] text-base font-bold">
                                        Menu Sehat
                                    </span>
                                </div>
                            </button>
                            <button
                                class="flex flex-col items-start bg-white text-left absolute bottom-[-16px] right-8 py-4 px-[15px] rounded-[32px] border-0"
                                style="box-shadow: 0px 20px 40px #765A001C" onclick="alert('Pressed!')"}>
                                <div class="flex items-center gap-3">
                                    <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/df5d38bb-c05f-4662-8702-bda517c31075"
                                        class="w-[21px] h-[19px] rounded-[32px] object-fill" />
                                    <div class="flex flex-col shrink-0 items-start py-[5px] px-[1px]">
                                        <span class="text-[#201B11] text-base font-bold">
                                            Reminder
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- SECTION FITUR --}}
        <section id="fitur" class="py-20 bg-[#FDF2E180]">

            <div class="max-w-7xl mx-auto px-6">

                {{-- Heading --}}
                <div class="text-center mb-16">
                    <h2 class="text-[32px] font-bold text-[#18542A]">
                        Fitur Gokil Buat Kamu
                    </h2>
                </div>

                {{-- Grid Fitur --}}
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

                    {{-- Hitung Kalori --}}
                    <div
                        class="bg-[#FFC92680] hover:bg-[#E5B422]
                       rounded-[48px] p-10 shadow-lg
                       transition-all duration-300
                       hover:-translate-y-2 hover:shadow-2xl">

                        <div class="w-12 h-12 rounded-full bg-white/50 flex items-center justify-center mb-6">
                            🔥
                        </div>

                        <h3 class="text-2xl font-bold text-[#201B11] mb-4">
                            Hitung Kalori Otomatis
                        </h3>

                        <p class="text-[#4E4633] leading-6">
                            Hitung kebutuhan kalori harianmu secara otomatis berdasarkan
                            tinggi, berat, usia, dan aktivitas.
                        </p>

                    </div>

                    {{-- Notifikasi --}}
                    <div
                        class="bg-[#D5251880] hover:bg-[#B71E13]
                       rounded-[48px] p-10 shadow-lg
                       transition-all duration-300
                       hover:-translate-y-2 hover:shadow-2xl">

                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-6">
                            🔔
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">
                            Notifikasi & Reminder
                        </h3>

                        <p class="text-white leading-6">
                            Dapatkan pengingat waktu makan agar pola makanmu tetap
                            teratur setiap hari.
                        </p>

                    </div>

                    {{-- Menu Nusantara --}}
                    <div
                        class="bg-[#9ABC0580] hover:bg-[#7D9804]
                       rounded-[48px] p-10 shadow-lg
                       transition-all duration-300
                       hover:-translate-y-2 hover:shadow-2xl">

                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-6">
                            🍽️
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">
                            Menu Nusantara
                        </h3>

                        <p class="text-white leading-6">
                            Ribuan resep sehat asli Indonesia di ujung jarimu.
                        </p>

                    </div>

                    {{-- Variasi Menu --}}
                    <div
                        class="bg-[#18542A80] hover:bg-[#123F20]
                       rounded-[48px] p-10 shadow-lg
                       transition-all duration-300
                       hover:-translate-y-2 hover:shadow-2xl">

                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-6">
                            🥗
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-4">
                            Variasi Menu Otomatis
                        </h3>

                        <p class="text-white leading-6">
                            Nikmati menu yang bervariasi setiap hari tanpa bosan.
                        </p>

                    </div>

                    {{-- Rekomendasi Menu --}}
                    <div
                        class="bg-[#F3E8CC80] hover:bg-[#DDD0B2]
                       rounded-[48px] p-10 shadow-lg
                       transition-all duration-300
                       hover:-translate-y-2 hover:shadow-2xl">

                        <div class="w-12 h-12 rounded-full bg-black/20 flex items-center justify-center mb-6">
                            📋
                        </div>

                        <h3 class="text-2xl font-bold text-black mb-4">
                            Rekomendasi Menu Harian
                        </h3>

                        <p class="text-black leading-6">
                            Dapatkan menu sarapan, makan siang, dan malam yang sesuai
                            dengan kebutuhan nutrisimu.
                        </p>

                    </div>

                    {{-- Alergi Filter --}}
                    <div
                        class="bg-[#F9601580] hover:bg-[#DE5210]
                       rounded-[48px] p-10 shadow-lg
                       transition-all duration-300
                       hover:-translate-y-2 hover:shadow-2xl">

                        <div class="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center mb-6">
                            ⚠️
                        </div>

                        <h3 class="text-2xl font-bold text-[#18542A] mb-4">
                            Alergi Filter
                        </h3>

                        <p class="text-[#4E4633] leading-6">
                            Hindari makanan yang tidak cocok dengan kondisi tubuhmu.
                        </p>

                    </div>

                </div>

            </div>

        </section>

        {{-- CARA KERJA --}}
        <section id="cara-kerja" class="py-20 bg-[#F3E8CC]">

            <div class="max-w-7xl mx-auto px-6">

                {{-- Heading --}}
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-[#18542A]">
                        Cara Kerjanya Gampang!
                    </h2>
                </div>

                {{-- Timeline --}}
                <div class="relative">

                    {{-- Garis penghubung desktop --}}
                    <div class="hidden lg:block absolute top-10 left-0 right-0 h-1 bg-[#E6D6B8]"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 relative">

                        {{-- Step 1 --}}
                        <div class="flex flex-col items-center text-center">

                            <div
                                class="w-20 h-20 rounded-full bg-[#FFC926]
                                shadow-[0_20px_40px_rgba(118,90,0,0.12)]
                                flex items-center justify-center
                                text-2xl font-semibold text-[#201B11]
                                mb-4 z-10">
                                1
                            </div>

                            <h3 class="text-2xl font-bold text-[#201B11] mb-3">
                                Input Data
                            </h3>

                            <p class="text-[#4E4633] leading-6">
                                Tulis tinggi, berat, dan goal kamu.
                            </p>

                        </div>

                        {{-- Step 2 --}}
                        <div class="flex flex-col items-center text-center">

                            <div
                                class="w-20 h-20 rounded-full bg-[#FFDBCE]
                                shadow-[0_20px_40px_rgba(118,90,0,0.12)]
                                flex items-center justify-center
                                text-2xl font-semibold text-[#201B11]
                                mb-4 z-10">
                                2
                            </div>

                            <h3 class="text-2xl font-bold text-[#201B11] mb-3">
                                Hitung Kalori
                            </h3>

                            <p class="text-[#4E4633] leading-6">
                                Biar kita tahu butuh berapa energi.
                            </p>

                        </div>

                        {{-- Step 3 --}}
                        <div class="flex flex-col items-center text-center">

                            <div
                                class="w-20 h-20 rounded-full bg-[#FFC2B8]
                                shadow-[0_20px_40px_rgba(118,90,0,0.12)]
                                flex items-center justify-center
                                text-2xl font-semibold text-[#201B11]
                                mb-4 z-10">
                                3
                            </div>

                            <h3 class="text-2xl font-bold text-[#201B11] mb-3">
                                Filter Alergi
                            </h3>

                            <p class="text-[#4E4633] leading-6">
                                Biar aman, pilih apa yang gak kamu makan.
                            </p>

                        </div>

                        {{-- Step 4 --}}
                        <div class="flex flex-col items-center text-center">

                            <div
                                class="w-20 h-20 rounded-full bg-[#EBE1D1]
                                shadow-[0_20px_40px_rgba(118,90,0,0.12)]
                                flex items-center justify-center
                                text-2xl font-semibold text-[#201B11]
                                mb-4 z-10">
                                4
                            </div>

                            <h3 class="text-2xl font-bold text-[#201B11] mb-3">
                                Menu Baru!
                            </h3>

                            <p class="text-[#4E4633] leading-6">
                                Voila! Menu sehat siap kamu sikat.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- ARTIKEL KESEHATAN --}}
        <section id="artikel" class="py-20 bg-[#FDF2E1]">

            <div class="max-w-7xl mx-auto px-6">

                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">

                    <div>
                        <h2 class="text-4xl font-extrabold text-[#201B0A] mb-3">
                            Artikel Kesehatan
                        </h2>

                        <p class="text-[#454935]">
                            Update informasi nutrisi dan tips gaya hidup sehat.
                        </p>
                    </div>

                    <a href="https://www.halodoc.com/artikel" target="_blank"
                        class="flex items-center gap-2 font-bold text-[#18542A] hover:text-[#D52518] transition">

                        Lihat Semua

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>

                    </a>

                </div>

                {{-- Cards --}}
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8 group">

                    {{-- Artikel 1 --}}
                    <a href="https://www.alodokter.com/5-makanan-sehat-yang-baik-untuk-tubuh" target="_blank"
                        class="bg-white rounded-3xl overflow-hidden shadow-lg
                      transition-all duration-500
                      blur-[3px] hover:blur-none
                      hover:scale-105 hover:shadow-2xl">

                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/ae05fff3-87e4-4176-bc90-240eb2d1a148"
                            alt="Superfood" class="w-full h-56 object-cover" />

                        <div class="p-8">

                            <span class="text-xs font-bold tracking-widest uppercase text-[#A73A00]">
                                Nutrisi
                            </span>

                            <h3 class="text-xl font-bold text-[#201B0A] mt-4 mb-4">
                                5 Superfood Murah yang Wajib Ada di Kos
                            </h3>

                            <p class="text-[#454935] text-sm leading-6 mb-5">
                                Makan sehat tidak harus mahal. Ini 5 bahan makanan
                                terjangkau dengan nutrisi maksimal.
                            </p>

                            <span class="font-bold text-[#BE0F07]">
                                Baca Selengkapnya →
                            </span>

                        </div>

                    </a>

                    {{-- Artikel 2 --}}
                    <a href="https://www.halodoc.com/artikel/pentingnya-mengatur-jam-makan" target="_blank"
                        class="bg-white rounded-3xl overflow-hidden shadow-lg
                      transition-all duration-500
                      blur-[3px] hover:blur-none
                      hover:scale-105 hover:shadow-2xl">

                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/294c6568-6da4-41d7-8e47-fbf2af664b37"
                            alt="Lifestyle" class="w-full h-64 object-cover" />

                        <div class="p-8">

                            <span class="text-xs font-bold tracking-widest uppercase text-[#9ABC05]">
                                Lifestyle
                            </span>

                            <h3 class="text-xl font-bold text-[#201B0A] mt-4 mb-4">
                                Cara Mengatur Jam Makan Saat Jadwal Kuliah Padat
                            </h3>

                            <p class="text-[#454935] text-sm leading-6 mb-5">
                                Bingung mengatur pola makan saat banyak tugas?
                                Simak tips manajemen waktu makan yang efektif.
                            </p>

                            <span class="font-bold text-[#BE0F07]">
                                Baca Selengkapnya →
                            </span>

                        </div>

                    </a>

                    {{-- Artikel 3 --}}
                    <a href="https://www.alodokter.com/sarapan-sehat-untuk-memulai-hari" target="_blank"
                        class="bg-white rounded-3xl overflow-hidden shadow-lg
                      transition-all duration-500
                      blur-[3px] hover:blur-none
                      hover:scale-105 hover:shadow-2xl">

                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/e296b1f7-c9c3-4211-a20a-45ed1d74274a"
                            alt="Resep" class="w-full h-64 object-cover" />

                        <div class="p-8">

                            <span class="text-xs font-bold tracking-widest uppercase text-[#765A00]">
                                Resep
                            </span>

                            <h3 class="text-xl font-bold text-[#201B0A] mt-4 mb-4">
                                Menu Breakfast 10 Menit untuk Mahasiswa
                            </h3>

                            <p class="text-[#454935] text-sm leading-6 mb-5">
                                Resep sarapan sehat, cepat, dan mengenyangkan
                                yang bisa dibuat di dalam kos.
                            </p>

                            <span class="font-bold text-[#BE0F07]">
                                Baca Selengkapnya →
                            </span>

                        </div>

                    </a>

                </div>

            </div>

        </section>

        {{-- REKOMENDASI MENU LOKAL --}}
        <section class="py-20">

            <div class="max-w-7xl mx-auto px-6">

                {{-- Heading --}}
                <h2 class="text-[32px] font-bold text-[#18542A] mb-10">
                    Rekomendasi Menu Lokal
                </h2>

                {{-- Filter Provinsi --}}
                <div class="flex items-center self-stretch">
                    <button
                        class="flex flex-col shrink-0 items-start bg-transparent text-left py-4 px-[27px] mr-[13px] rounded-[9999px] border-2 border-solid border-[#D2C5AC]"
                        onclick="alert('Pressed!')"}>
                        <span class="text-[#201B11] text-base">
                            Banten
                        </span>
                    </button>
                    <button
                        class="flex flex-col shrink-0 items-start bg-transparent text-left py-[15px] px-[27px] mr-3 rounded-[9999px] border-2 border-solid border-[#D2C5AC]"
                        onclick="alert('Pressed!')"}>
                        <span class="text-[#201B11] text-base">
                            DKI Jakarta
                        </span>
                    </button>
                    <button
                        class="flex flex-col shrink-0 items-start bg-transparent text-left py-4 px-[26px] mr-3 rounded-[9999px] border-2 border-solid border-[#D2C5AC]"
                        onclick="alert('Pressed!')"}>
                        <span class="text-[#201B11] text-base">
                            Jawa Barat
                        </span>
                    </button>
                    <button
                        class="flex flex-col shrink-0 items-start bg-transparent text-left py-3.5 px-[26px] mr-[13px] rounded-[9999px] border-2 border-solid border-[#D2C5AC]"
                        onclick="alert('Pressed!')"}>
                        <span class="text-[#201B11] text-base">
                            Jawa Tengah
                        </span>
                    </button>
                    <button
                        class="flex flex-col shrink-0 items-start bg-transparent text-left py-2.5 px-[25px] mr-3 rounded-[9999px] border-2 border-solid border-[#D2C5AC]"
                        onclick="alert('Pressed!')"}>
                        <span class="text-black text-base">
                            DI Yogyakarta
                        </span>
                    </button>
                    <button
                        class="flex flex-col shrink-0 items-start bg-transparent text-left py-2.5 px-[25px] rounded-[9999px] border-2 border-solid border-[#D2C5AC]"
                        onclick="alert('Pressed!')"}>
                        <span class="text-[#201B11] text-base">
                            Jawa Timur
                        </span>
                    </button>
                </div>

                {{-- Cards --}}
                <div class="flex items-center self-stretch py-5 gap-[25px]">
                    <div class="flex flex-1 items-center bg-white py-3 rounded-[48px]"
                        style="box-shadow: 0px 8px 30px #765A000D">
                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/71cd886d-3daf-4271-a9fd-2e1139ab9052"
                            class="w-20 h-20 ml-3 mr-6 rounded-[48px] object-fill" />
                        <div class="flex flex-col shrink-0 items-start">
                            <div class="flex flex-col items-center py-1">
                                <span class="text-[#201B11] text-base font-bold">
                                    Gudeg (Yogyakarta)
                                </span>
                            </div>
                            <span class="text-[#BE0F07] text-sm font-bold mr-[103px]">
                                450 kcal
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-1 items-center bg-white py-3 rounded-[48px]"
                        style="box-shadow: 0px 8px 30px #765A000D">
                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/2e0cf57d-b331-4ee3-811d-c6943756ae7f"
                            class="w-20 h-20 ml-[11px] mr-6 rounded-[48px] object-fill" />
                        <div class="flex flex-col shrink-0 items-center">
                            <span class="text-[#201B11] text-base font-bold">
                                Rawon (Jawa Timur)
                            </span>
                            <div class="flex flex-col items-start pr-[110px]">
                                <span class="text-[#BE0F07] text-sm font-bold">
                                    380 kcal
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-1 items-center bg-white py-3 rounded-[48px]"
                        style="box-shadow: 0px 8px 30px #765A000D">
                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/5a530461-13c6-4823-a8c2-8c8d1adc814e"
                            class="w-20 h-20 ml-[11px] mr-6 rounded-[48px] object-fill" />
                        <div class="flex flex-col shrink-0 items-center">
                            <div class="flex flex-col items-center py-1">
                                <span class="text-[#201B11] text-base font-bold">
                                    Soto Betawi (Jakarta)
                                </span>
                            </div>
                            <div class="flex flex-col items-start pr-[119px]">
                                <span class="text-[#BE0F07] text-sm font-bold">
                                    410 kcal
                                </span>
                            </div>
                        </div>
                    </div>
        </section>

        {{-- FINAL CTA --}}
        <section class="py-20 px-6">

            <div
                class="relative max-w-7xl mx-auto overflow-hidden rounded-[48px]
               bg-gradient-to-r from-[#BE0F07] to-[#FF641A]
               shadow-[0_20px_40px_rgba(118,90,0,0.12)]">

                {{-- Blur Decoration --}}
                <div
                    class="absolute -left-16 bottom-0 w-64 h-64
                   bg-white/10 blur-3xl rounded-[154px_51px_166px_90px]">
                </div>

                <div class="relative z-10 px-8 md:px-16 py-16 text-center">

                    {{-- Heading --}}
                    <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-8 leading-tight">
                        Siap Hidup Lebih Berwarna?
                    </h2>

                    {{-- Description --}}
                    <p class="max-w-2xl mx-auto text-lg md:text-xl text-white/90 leading-relaxed mb-12">
                        Ribuan Gen Z sudah mulai perjalanannya. Giliran kamu sekarang
                        untuk merasa lebih sehat, lebih segar, dan tetap nikmat.
                    </p>

                    {{-- Button --}}
                    <a href="{{ route('user.dashboard') }}"
                        class="inline-flex items-center justify-center
                       bg-white text-[#BE0F07]
                       px-10 py-5 rounded-full
                       text-lg md:text-xl font-bold
                       shadow-xl
                       transition-all duration-300
                       hover:scale-105
                       hover:shadow-2xl">

                        Coba Sekarang

                    </a>

                </div>

            </div>

        </section>




        <div class="flex items-start self-stretch bg-emerald-900 pt-20 pb-8 px-12 rounded-tl-[64px] rounded-tr-[64px]">
            <div class="flex flex-col shrink-0 items-center pb-[81px] mr-12 gap-[23px]">
                <div class="flex flex-col items-start pr-[183px]">
                    <span class="text-yellow-400 text-xl font-bold">
                        NutriGo
                    </span>
                </div>
                <div class="flex flex-col items-start pr-[35px]">
                    <span class="text-white text-sm w-[225px]">
                        © 2026 NutriGo.<br />Stay Juicy. Stay Healthy. Be Happy.
                    </span>
                </div>
            </div>
            <div class="flex flex-col shrink-0 items-center gap-6">
                <div class="flex flex-col items-start pr-[203px]">
                    <span class="text-yellow-400 text-base font-bold">
                        Explore
                    </span>
                </div>
                <div class="flex flex-col items-center gap-3">
                    <div class="flex flex-col items-start py-0.5 pr-[201px]">
                        <span class="text-stone-100 text-sm">
                            About Us
                        </span>
                    </div>
                    <div class="flex flex-col items-start py-0.5 pr-[167px]">
                        <span class="text-stone-100 text-sm">
                            NutriGo Guide
                        </span>
                    </div>
                    <div class="flex flex-col items-start py-0.5 pr-[172px]">
                        <span class="text-stone-100 text-sm">
                            Privacy Policy
                        </span>
                    </div>
                    <div class="flex flex-col items-start py-0.5 pr-[207px]">
                        <span class="text-stone-100 text-sm">
                            Contact
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex-1 self-stretch">
            </div>
            <div class="flex flex-col shrink-0 items-center pb-[92px] gap-6">
                <div class="flex flex-col items-start pr-[206px]">
                    <span class="text-yellow-400 text-base font-bold">
                        Socials
                    </span>
                </div>
                <div class="flex items-center">
                    <button
                        class="flex flex-col shrink-0 items-start bg-[#FFFFFF1A] text-left p-3 mr-6 rounded-[9999px] border-0"
                        onclick="alert('Pressed!')"}>
                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/a56f6536-ffe8-492c-af91-39eaa390a7d3"
                            class="w-[15px] h-[15px] rounded-[9999px] object-fill" />
                    </button>
                    <button
                        class="flex flex-col shrink-0 items-start bg-[#FFFFFF1A] text-left p-3 mr-6 rounded-[9999px] border-0"
                        onclick="alert('Pressed!')"}>
                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/840e0830-2dca-4a8e-affb-73b66505aa1c"
                            class="w-[15px] h-[15px] rounded-[9999px] object-fill" />
                    </button>
                    <button
                        class="flex flex-col shrink-0 items-start bg-[#FFFFFF1A] text-left p-3 mr-[92px] rounded-[9999px] border-0"
                        onclick="alert('Pressed!')"}>
                        <img src="https://figma-alpha-api.s3.us-west-2.amazonaws.com/images/db3953c9-4678-4f63-bb46-62a53facc809"
                            class="w-[15px] h-[15px] rounded-[9999px] object-fill" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
