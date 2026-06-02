@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')
<div class="space-y-6 py-2">
    @if(!empty($needsOnboarding))
        @php
            $currentAllergens = $user->allergies->pluck('allergen')->map(fn ($allergen) => strtolower($allergen))->all();
            $hasMedicalNeed = $user->medicalNeeds()->where('is_active', true)->exists();
            $initialHasAllergy = old('has_allergy', count($currentAllergens) > 0 ? 'yes' : 'no');
            $initialHasMedicalNeed = old('has_medical_need', $hasMedicalNeed ? 'yes' : 'no');
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 p-4">
            <div class="grid w-full max-w-5xl overflow-hidden rounded-[28px] bg-[#fff8ea] shadow-[0_30px_90px_rgba(0,0,0,0.35)] ring-1 ring-black/5 lg:max-h-[90vh] lg:grid-cols-[0.9fr_1.1fr]">
                <div class="hidden bg-gradient-to-br from-[#1d5b2f] via-[#195528] to-[#0e3d1f] p-8 text-white lg:flex lg:flex-col lg:justify-between">
                    <div>
                        <img src="{{ asset('assets/Logo NutriGo 2.png') }}" alt="NutriGo" class="h-14 w-auto brightness-0 invert">
                        <h3 class="mt-6 text-3xl font-black leading-tight text-[#fff8ea]">Lengkapi profil untuk membuka dashboard penuh</h3>
                        <p class="mt-3 max-w-md text-sm leading-6 text-white/80">Data BMI kamu sudah tersimpan. Selesaikan nama panggilan, wilayah, alergi, dan kebutuhan khusus supaya rekomendasi dan dashboard bisa menyesuaikan profilmu.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm text-white/85">
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-white/60">Langkah 1</p>
                            <p class="mt-2 font-semibold">Nama panggilan</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-white/60">Langkah 2</p>
                            <p class="mt-2 font-semibold">Region & daerah</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-white/60">Langkah 3</p>
                            <p class="mt-2 font-semibold">Alergi makanan</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-white/60">Langkah 4</p>
                            <p class="mt-2 font-semibold">Kebutuhan khusus</p>
                        </div>
                    </div>
                </div>

                <div class="relative bg-[#fff3cb] p-6 sm:p-8 lg:max-h-[90vh] lg:overflow-y-auto" x-data="{ hasAllergy: '{{ $initialHasAllergy }}', hasMedical: '{{ $initialHasMedicalNeed }}' }">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-black text-[#17311f]">Lengkapi profilmu</h3>
                            <p class="mt-1 text-sm text-[#7a674e]">Isi data yang belum ada supaya dashboard dan rekomendasi bisa aktif penuh.</p>
                        </div>
                        <div class="rounded-full bg-[#fff8d6] px-4 py-2 text-xs font-bold uppercase tracking-[0.25em] text-[#f55c1f]">Onboarding</div>
                    </div>

                    <form method="POST" action="{{ route('user.profile.complete') }}" class="mt-6 space-y-5">
                        @csrf
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Nama panggilan</label>
                            <input type="text" name="nickname" value="{{ old('nickname', $user->nickname) }}" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] placeholder:text-[#9c8e6f] focus:ring-2 focus:ring-[#ffd16b]" placeholder="Panggil aku..." required>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Provinsi</label>
                                <select name="province" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] focus:ring-2 focus:ring-[#ffd16b]" required>
                                    <option value="">Pilih provinsi</option>
                                    @foreach(($provinceOptions ?? []) as $province)
                                        <option value="{{ $province }}" {{ old('province', $user->province) === $province ? 'selected' : '' }}>{{ $province }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Kota / Kabupaten</label>
                                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] placeholder:text-[#9c8e6f] focus:ring-2 focus:ring-[#ffd16b]" placeholder="Contoh: Bandung" required>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <label class="text-sm font-semibold text-[#4a4034]">Apakah kamu punya alergi makanan?</label>
                                <div class="flex rounded-full bg-[#fff8d6] p-1 text-xs font-bold uppercase tracking-[0.2em] text-[#17311f]">
                                    <button type="button" @click="hasAllergy='yes'" :class="hasAllergy === 'yes' ? 'bg-[#f55c1f] text-white' : 'text-[#17311f]'" class="rounded-full px-3 py-2 transition">Ya</button>
                                    <button type="button" @click="hasAllergy='no'" :class="hasAllergy === 'no' ? 'bg-[#1d5b2f] text-white' : 'text-[#17311f]'" class="rounded-full px-3 py-2 transition">Tidak</button>
                                </div>
                            </div>
                            <input type="hidden" name="has_allergy" :value="hasAllergy">
                            <div x-show="hasAllergy === 'yes'" x-transition class="space-y-4">
                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach(($allergyOptions ?? []) as $allergy)
                                        <label class="flex items-center gap-2 rounded-2xl border border-[#d9c8a1] bg-[#fff7e7] px-3 py-3 text-sm text-[#17311f] transition hover:border-[#f55c1f] hover:text-[#f55c1f]">
                                            <input type="checkbox" name="allergens[]" value="{{ $allergy }}" {{ in_array(strtolower($allergy), $currentAllergens, true) ? 'checked' : '' }} class="rounded border-[#d9c8a1] text-[#f55c1f] focus:ring-[#f55c1f]">
                                            <span>{{ $allergy }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Alergi lainnya</label>
                                    <input type="text" name="custom_allergy" value="{{ old('custom_allergy') }}" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] placeholder:text-[#9c8e6f] focus:ring-2 focus:ring-[#ffd16b]" placeholder="Contoh: durian, pete...">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <label class="text-sm font-semibold text-[#4a4034]">Ada kebutuhan khusus?</label>
                                <div class="flex rounded-full bg-[#fff8d6] p-1 text-xs font-bold uppercase tracking-[0.2em] text-[#17311f]">
                                    <button type="button" @click="hasMedical='yes'" :class="hasMedical === 'yes' ? 'bg-[#f55c1f] text-white' : 'text-[#17311f]'" class="rounded-full px-3 py-2 transition">Ya</button>
                                    <button type="button" @click="hasMedical='no'" :class="hasMedical === 'no' ? 'bg-[#1d5b2f] text-white' : 'text-[#17311f]'" class="rounded-full px-3 py-2 transition">Tidak</button>
                                </div>
                            </div>
                            <input type="hidden" name="has_medical_need" :value="hasMedical">
                            <div x-show="hasMedical === 'yes'" x-transition class="space-y-4">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Makanan / bahan yang dibutuhkan</label>
                                        <input type="text" name="food_item" value="{{ old('food_item') }}" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] placeholder:text-[#9c8e6f] focus:ring-2 focus:ring-[#ffd16b]" placeholder="Contoh: telur, susu, wortel">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Jumlah</label>
                                        <input type="number" name="quantity" min="1" value="{{ old('quantity') }}" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] placeholder:text-[#9c8e6f] focus:ring-2 focus:ring-[#ffd16b]" placeholder="2">
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Satuan</label>
                                        <select name="unit" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] focus:ring-2 focus:ring-[#ffd16b]">
                                            <option value="">Pilih satuan</option>
                                            <option value="butir" {{ old('unit') === 'butir' ? 'selected' : '' }}>butir</option>
                                            <option value="porsi" {{ old('unit') === 'porsi' ? 'selected' : '' }}>porsi</option>
                                            <option value="gram" {{ old('unit') === 'gram' ? 'selected' : '' }}>gram</option>
                                            <option value="ml" {{ old('unit') === 'ml' ? 'selected' : '' }}>ml</option>
                                            <option value="gelas" {{ old('unit') === 'gelas' ? 'selected' : '' }}>gelas</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="mb-2 block text-sm font-semibold text-[#4a4034]">Durasi</label>
                                        <select name="duration_type" class="w-full rounded-2xl border-0 bg-[#fff1d8] px-4 py-3 text-[#17311f] focus:ring-2 focus:ring-[#ffd16b]">
                                            <option value="">Pilih durasi</option>
                                            <option value="daily" {{ old('duration_type') === 'daily' ? 'selected' : '' }}>Setiap hari</option>
                                            <option value="weekly" {{ old('duration_type') === 'weekly' ? 'selected' : '' }}>Seminggu sekali</option>
                                            <option value="yearly" {{ old('duration_type') === 'yearly' ? 'selected' : '' }}>Setahun</option>
                                            <option value="forever" {{ old('duration_type') === 'forever' ? 'selected' : '' }}>Seterusnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($errors->any())
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <p class="font-bold">Ada data yang perlu diperbaiki.</p>
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3 pt-2">
                            <button type="submit" class="flex-1 rounded-full bg-[#f55c1f] px-6 py-4 text-sm font-extrabold text-white shadow-[0_10px_24px_rgba(245,92,31,0.24)] transition hover:brightness-105">Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

   <section class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-[#18542D] via-[#1F6E3A] to-[#2A7A43] p-8 lg:p-10 text-white shadow-[0_20px_60px_rgba(20,58,31,0.16)]">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex rounded-full bg-[#f55c1f] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-white/95">Nutrition Insight</span>
                <h2 class="mt-4 text-3xl font-black leading-tight text-[#fff8ea] sm:text-3.5xl">Menu hari ini sudah disesuaikan dengan kebutuhan kalorimu.</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-white/80 sm:text-base">Selamat datang, {{ $user->nickname ?? $user->name }}. Data kesehatanmu sudah tersimpan, jadi rekomendasi menu dan pengingat makan bisa menyesuaikan kebutuhan harianmu.</p>
                <div class="mt-8 flex flex-wrap gap-3 items-center">
                <button
                    type="button"
                    id="loadRecommendationsBtn"
                    onclick="document.getElementById('recommendation-list').scrollIntoView({ behavior: 'smooth' })"
                    class="inline-flex items-center gap-2 rounded-full bg-[#ffc926] px-6 py-4 text-base font-extrabold text-[#17311f] transition hover:bg-[#ffd953]">
                    Lihat rekomendasi lengkap
                </button>
                </div>
            </div>
        </div>
        </div>
    </section>

    </section>
        <div class="mt-5 grid grid-cols-2 gap-5 lg:grid-cols-4">
        <div class="rounded-2xl bg-[#F67A2A] p-5 text-white shadow-sm ring-1 ring-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-[0.2em] text-white/75"><span>BMI Kamu</span><span>i</span></div>
            <p class="mt-6 text-4xl font-black">{{ $user->bmi ? number_format($user->bmi, 1) : '—' }}</p>
            @php
                $bmiCategory = $user->bmi
                    ? match(true) {
                        $user->bmi < 18.5 => 'Kurus',
                        $user->bmi < 25 => 'Normal',
                        $user->bmi < 30 => 'Overweight',
                        default => 'Obesitas',
                    }
                    : 'Belum dihitung';
            @endphp
            <p class="mt-2 text-sm font-semibold text-white/80">{{ $bmiCategory }}</p>
        </div>
        <div class="rounded-2xl bg-[#F67A2A] p-5 text-white shadow-sm ring-1 ring-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-[0.2em] text-white/75"><span>Total Kalori</span><span>◌</span></div>
            <p class="mt-6 text-4xl font-black">{{ number_format($user->daily_calorie_needs ?? 0) }}</p>
            <p class="mt-2 text-sm font-semibold text-white/80">Target Harian</p>
        </div>
        <div class="rounded-2xl bg-[#F67A2A] p-5 text-white shadow-sm ring-1 ring-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-[0.2em] text-white/75"><span>Aktivitas</span><span>⌄</span></div>
            <p class="mt-6 text-2xl font-black">{{ ucfirst($user->activity_level ?? 'moderate') }}</p>
            <p class="mt-2 text-sm font-semibold text-white/80">{{ ucfirst($user->activity_level ?? 'moderate') }}</p>
        </div>
        <div class="rounded-2xl bg-[#F67A2A] p-5 text-white shadow-sm ring-1 ring-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-[0.2em] text-white/75"><span>Alergi</span><span>⌃</span></div>
            <div class="mt-5 flex flex-wrap gap-2">
                @forelse($allergies as $allergy)
                    <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">{{ $allergy }}</span>
                @empty
                    <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">Belum ada</span>
                @endforelse
            </div>
        </div>
    </div>

    <section id="recommendation-list" class="mt-6 rounded-[32px] bg-[#F3E8CC] p-8 shadow-sm border border-[#ece1c1]">
    <div class="flex items-center justify-between mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#18542D]/60">
                PERSONALISASI MENU
            </span>

            <h2 class="mt-2 text-3xl font-black text-[#17311f]">
                Rekomendasi Menu Hari Ini
            </h2>

            <p class="mt-2 text-[#4d5a4f]">
                Disesuaikan dengan kebutuhan kalori dan wilayahmu.
            </p>
        </div>

            <button
                id="refreshRecommendationsBtn"
                class="rounded-full bg-gradient-to-r from-[#18542D] to-[#2B7A43] px-5 py-3 text-sm font-bold text-white hover:from-[#2B7A43] hover:to-[#18542D] transition">
                Ganti Rekomendasi
            </button>
        </div>

@include('user.partials.recommendation_cards', [
            'menu' => [
                'breakfast' => $menuRec['breakfast'] ?? null,
                'lunch' => $menuRec['lunch'] ?? null,
                'dinner' => $menuRec['dinner'] ?? null
            ]
        ])

    </section>

    <div class="mt-6">
        <div class="space-y-5">
            {{-- Detailed meal cards moved into #recommendation-list to avoid duplication --}}

           <section class="mt-2 rounded-[28px] bg-[#F3E8CC] border border-[#E8DBB8] p-6 shadow-[0_12px_26px_rgba(161,114,0,0.16)]">
                <h3 class="text-xl font-black text-[#17311f]">Pengingat Makan</h3>

<div class="mt-6 rounded-[24px] bg-[#F3E8CC] p-6 shadow-lg">
                    {{-- Timeline wrapper --}}

                    <div class="relative">
                        {{-- vertical connector background --}}
                        <div class="absolute left-[18px] top-0 bottom-0 w-px bg-white/40"></div>

                        @foreach($reminders as $r)
                            @php
                                $meal = $r->meal_type;

                                $planned = $menuRec[$meal] ?? null;
                                $completedFoodHistory = $historyByMealType[$meal] ?? null;

                                $now = \Carbon\Carbon::now();
                                $reminderAt = \Carbon\Carbon::parse($r->reminder_time)->today();

                                // Completed if already logged for today
                                $isCompleted = (bool) $completedFoodHistory;

                                // Current = not completed and time passed for its reminder slot
                                $isCurrent = !$isCompleted && $reminderAt->lessThanOrEqualTo($now);

                                $statusLabel = $isCompleted ? 'Completed' : ($isCurrent ? 'Current Meal' : 'Upcoming');

                                $selectedFood = $isCompleted ? ($completedFoodHistory->food ?? null) : $planned;

                                $stepDotClass = $isCompleted
                                    ? 'bg-[#18542D]'
                                    : ($isCurrent ? 'bg-[#F96015]' : 'bg-white/30');

                                $stepCardClass = $isCompleted
                                    ? 'bg-white/10 opacity-60'
                                    : ($isCurrent ? 'bg-[#fff8ea] border-[#18542D]/30' : 'bg-white/15');

                                $stepTextClass = $isCompleted ? 'text-white/80' : 'text-white';
                            @endphp

                            {{-- Step row --}}
                            <div class="relative flex gap-4 pb-6 @if($loop->last) pb-0 @endif">
                                {{-- Dot + left alignment --}}
                                <div class="z-10 flex flex-col items-center" style="width:40px">
                                    <div class="h-5 w-5 rounded-full border-4 border-white {{ $stepDotClass }} flex items-center justify-center">
                                        @if($isCompleted)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8.5 8.5a1 1 0 01-1.414 0l-3.0-3.0a1 1 0 011.414-1.414l2.293 2.293 7.793-7.793a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                {{-- Card --}}
                                <div class="flex-1 rounded-2xl border border-white/20 backdrop-blur-sm px-5 py-4 {{ $stepCardClass }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-black text-[#18542D]">
                                                {{ ucfirst($r->meal_type === 'breakfast' ? 'Makan Pagi' : ($r->meal_type === 'lunch' ? 'Makan Siang' : 'Makan Malam')) }}
                                            </p>
                                            <p class="text-xs text-[#18542D]/70">{{ \Carbon\Carbon::parse($r->reminder_time)->format('H:i') }}</p>

                                            <div class="mt-2">
                                                @php
                                                    $statusBg = $isCompleted ? '#9ABC05' : ($isCurrent ? '#FFC926' : 'rgba(255,255,255,0.0)');
                                                    $statusFg = $isCompleted ? '#FFFFFF' : '#18542D';
                                                @endphp
                                                <p class="text-xs font-bold uppercase tracking-[0.12em]" style="background-color: {{ $statusBg }}; color: {{ $statusFg }}; padding: 6px 10px; border-radius: 9999px; display: inline-block;">
                                                    {{ $statusLabel }}
                                                </p>


                                                @if($selectedFood)
                                                    <p class="text-sm font-semibold mt-2 {{ $stepTextClass }}">
                                                        {{ $selectedFood->name }}
                                                    </p>
                                                    <div class="mt-2 flex gap-2 flex-wrap">
                                                        <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold">{{ $selectedFood->calories }} kkal</span>
                                                        <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold">{{ $selectedFood->proteins }}g Pro</span>
                                                        <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold">{{ $selectedFood->carbohydrate ?? ($selectedFood->carbohydrates ?? '—') }}g Karbo</span>
                                                    </div>
                                                @else
                                                    <p class="text-sm mt-2 {{ $stepTextClass }}">Belum ada menu terpilih</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-2">
                                            @if($isCompleted)
                                                <div class="flex items-center gap-2">
                                                    <span class="rounded-full bg-white/15 px-3 py-2 text-xs font-bold text-white">Logged</span>
                                                </div>
                                            @elseif($isCurrent)
                                                {{-- Current actions --}}
                                                @if($selectedFood)
<button class="confirm-planned-btn rounded-full bg-[#18542D] px-4 py-2 text-xs font-bold text-white hover:bg-[#2B7A43] transition" data-meal="{{ $r->meal_type }}" type="button">Konfirmasi Log Menu</button>
                                                    <a href="{{ route('user.menu') }}?meal_type={{ $r->meal_type }}" class="ganti-menu rounded-full bg-white/10 px-3 py-2 text-sm font-bold text-white">Ganti Menu</a>
                                                @else
                                                    <a href="{{ route('user.menu') }}?meal_type={{ $r->meal_type }}" class="rounded-full bg-white px-3 py-2 text-sm font-bold text-[#1d5b2f]">Pilih Menu</a>
                                                @endif
                                            @else
                                                {{-- Upcoming inactive --}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<div x-data="dashboardDraftSync()" x-init="init()"></div>

<script>
    // Recommendation filter behaviour
    document.addEventListener('DOMContentLoaded', function () {
        const province = document.getElementById('quickProvince');
        const activity = document.getElementById('quickActivity');
        const allergen = document.getElementById('quickAllergen');
        const loadBtn = document.getElementById('loadRecommendationsBtn');
        const refreshBtn = document.getElementById('refreshRecommendationsBtn');


        function updateButtonState() {
            loadBtn.disabled = !province || !province.value;
        }

        async function fetchRecommendations() {
            if (!province || !province.value) return;
            const payload = {
                province: province.value,
                activity_level: activity?.value || null,
                allergens: allergen && allergen.value ? [allergen.value] : null,
            };

            const res = await fetch(@json(route('user.recommendations.filter')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload),
            });

            if (res.ok) {
                const data = await res.json();
                if (data.success) {
                    document.getElementById('recommendation-list').innerHTML = data.html;
                    window.scrollTo({top: document.getElementById('recommendation-list').offsetTop - 80, behavior: 'smooth'});
                }
            }
        }

        province?.addEventListener('change', function () {
            updateButtonState();
            // legacy: keep recommendation filter behavior disabled for planned menu sync
            // if you later want filtered regeneration, re-enable fetchRecommendations()
        });

        loadBtn?.addEventListener('click', async function () {
            window.location.href = @json(route('user.dashboard'));
        });

        refreshBtn?.addEventListener('click', async function () {
            const res = await fetch(@json(route('user.menu.regenerate')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({}),
            });

            window.location.href = @json(route('user.dashboard'));
        });

        updateButtonState();
    });

    function dashboardDraftSync() {
        return {
            async init() {
                const draft = localStorage.getItem('nutrigo.healthDraft');

                if (!draft) {
                    return;
                }

                try {
                    const payload = JSON.parse(draft);

                    if (!payload.height_cm || !payload.weight_kg || !payload.age || !payload.gender) {
                        return;
                    }

                    const response = await fetch(@json(route('user.health.store')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        body: JSON.stringify({
                            height_cm: payload.height_cm,
                            weight_kg: payload.weight_kg,
                            age: payload.age,
                            gender: payload.gender,
                            activity_level: payload.activity_level || 'moderate',
                        }),
                    });

                    if (response.ok) {
                        localStorage.removeItem('nutrigo.healthDraft');
                        const data = await response.json();
                        window.location.href = data.redirect || @json(route('user.dashboard'));
                    }
                } catch (error) {}
            },
        };
    }

    // Handle select menu (AJAX) and confirm planned menu
    document.addEventListener('submit', async function (e) {
        const form = e.target.closest && e.target.closest('.select-menu-form');
        if (!form) return;
        e.preventDefault();

        const url = form.action;
        const fd = new FormData(form);
        const body = Object.fromEntries(fd.entries());

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: fd,
            });

            if (res.ok) {
                const data = await res.json();
                showSuccessModal(data.message || 'Menu berhasil ditambahkan ke rencana.');
                // reload to update Pengingat Makan
                setTimeout(() => location.reload(), 900);
            } else {
                const err = await res.json();
                showSuccessModal(err.message || 'Gagal menambahkan menu.');
            }
        } catch (err) {
            showToast('Terjadi kesalahan.');
        }
    });

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest && e.target.closest('.confirm-planned-btn');
        if (!btn) return;
        const meal = btn.getAttribute('data-meal');
        if (!meal) return;
        try {
            const res = await fetch(@json(route('user.menu.confirm')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ meal_type: meal }),
            });
            if (res.ok) {
                const data = await res.json();
                showSuccessModal(data.message || 'Menu berhasil dicatat.');
                setTimeout(() => location.reload(), 900);
            } else {
                const err = await res.json();
                showSuccessModal(err.message || 'Gagal mencatat menu.');
            }
        } catch (err) {
            showToast('Terjadi kesalahan.');
        }
    });

    // Open food detail modal via AJAX
    document.addEventListener('click', async function (e) {
        const trigger = e.target.closest && e.target.closest('.open-food-detail');
        if (!trigger) return;
        e.preventDefault();
        const foodId = trigger.getAttribute('data-food-id');
        const mealType = trigger.getAttribute('data-meal-type') || null;
        if (!foodId) return;

        try {
            const res = await fetch("/dashboard/foods/" + encodeURIComponent(foodId), {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (!data.success) return;
            const div = document.createElement('div');
            div.innerHTML = data.html;
            document.body.appendChild(div);

            // attach events for modal buttons
            const modal = document.getElementById('food-modal-overlay');
            modal.querySelectorAll('.close-food-modal').forEach(btn => btn.addEventListener('click', () => modal.remove()));

            // Enable selection and redirect back to dashboard for synchronization.
            const selectBtn = modal.querySelector('.select-from-modal');
            if (selectBtn) {
                selectBtn.addEventListener('click', async function () {
                    const fid = this.getAttribute('data-food-id');
                    // Slot should come from the dashboard context, not from the modal.
                    const mtype = mealType || 'breakfast';

                    try {
                        const formData = new FormData();
                        formData.append('food_id', fid);
                        formData.append('meal_type', mtype);

                        const r = await fetch(@json(route('user.menu.select')), {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: formData,
                        });

                        const j = await r.json();
                        if (r.ok) {
                            // Force reload so both recommendation cards + reminder cards re-render from menu_recommendations.
                            window.location.href = @json(route('user.dashboard'));
                        } else {
                            showSuccessModal(j.message || 'Gagal menambahkan menu.');
                        }
                    } catch (err) {
                        showSuccessModal('Gagal menambahkan menu.');
                    }
                });
            }
        } catch (err) {}
    });

    // Ganti Menu links: append current province if selected
    document.addEventListener('click', function (e) {
        const a = e.target.closest && e.target.closest('.ganti-menu');
        if (!a) return;
        e.preventDefault();
        const province = document.getElementById('quickProvince');
        let url = a.href;
        if (province && province.value) {
            const sep = url.includes('?') ? '&' : '?';
            url = url + sep + 'province=' + encodeURIComponent(province.value);
        }
        window.location.href = url;
    });

        function showSuccessModal(message, title = null) {
                // load partial HTML
                const existing = document.getElementById('success-modal-overlay');
                if (existing) existing.remove();
                const div = document.createElement('div');
                div.innerHTML = `
                <div id="success-modal-overlay" class="fixed inset-0 z-70 flex items-center justify-center bg-black/40">
                    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-xl">
                        <div class="mx-auto h-16 w-16 rounded-full bg-[#dff5e6] flex items-center justify-center">
                            <svg class="h-8 w-8 text-[#1d5b2f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="mt-4 text-lg font-extrabold text-[#17311f]">${title || 'Berhasil'}</h3>
                        <p class="mt-2 text-sm text-[#6b5f46]">${message}</p>
                        <div class="mt-5">
                            <button id="success-modal-close" class="rounded-full bg-white border border-[#d9c8a1] px-6 py-3 font-bold text-[#1d5b2f]">Tutup</button>
                        </div>
                    </div>
                </div>
                `;
                document.body.appendChild(div);
                const overlay = document.getElementById('success-modal-overlay');
                document.getElementById('success-modal-close').addEventListener('click', () => overlay.remove());
                // auto-remove after 1.6s
                setTimeout(() => overlay.remove(), 1600);
        }
</script>
@endsection