@php
    $items = [$menu['breakfast'] ?? null, $menu['lunch'] ?? null, $menu['dinner'] ?? null];
    $regions = ['JAWA TIMUR', 'JAWA TIMUR', 'JAWA TIMUR'];
@endphp

<div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
    @foreach ($items as $index => $card)
        <article
            class="group overflow-hidden rounded-[28px] bg-white shadow-[0_15px_40px_rgba(24,84,42,0.08)] ring-1 ring-[#F3E8CC] transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_25px_50px_rgba(24,84,42,0.15)]">

            {{-- Header Card --}}
            <div class="bg-gradient-to-r from-[#18542A] to-[#236937] px-6 py-5">

                <div class="flex items-center justify-between">

                    <span
                        class="rounded-full bg-[#FFC926] px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.15em] text-[#18542A]">
                        {{ $regions[$index] ?? 'JAWA TIMUR' }}
                    </span>

                    <span class="rounded-full bg-white px-3 py-1 text-xs font-black text-[#F96015] shadow-sm">
                        {{ $card?->calories ?? '—' }} kcal
                    </span>

                </div>

            </div>

            {{-- Body --}}
            <div class="bg-[#ffff] p-6">

                @if ($card)
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">
                            {{ ['🌅', '🍽️', '🌙'][$index] ?? '🍴' }}
                        </span>

                        <span class="text-xs font-bold uppercase tracking-wider text-[#9ABC05]">
                            {{ ['Sarapan', 'Makan Siang', 'Makan Malam'][$index] ?? 'Menu' }}
                        </span>
                    </div>

                    <h3 class="mt-3 text-2xl font-black leading-tight text-[#18542A]">
                        {{ $card->name }}
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-[#75684F] line-clamp-3">
                        {{ $card->composition ?: 'Rekomendasi menu sehat yang sesuai dengan kebutuhanmu.' }}
                    </p>

                    <div class="mt-6 flex justify-end">
                        <a href="#"
                            class="open-food-detail inline-flex items-center gap-2 rounded-full bg-[#F96015] px-5 py-3 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-[#D52518] hover:shadow-lg"
                            data-food-id="{{ $card->id }}"
                            data-meal-type="{{ ['breakfast', 'lunch', 'dinner'][$index] ?? 'breakfast' }}">

                            Detail →
                        </a>
                    </div>
                @else
                    <div class="rounded-2xl bg-[#F3E8CC] p-5 text-center">

                        <div class="text-5xl">🍽️</div>

                        <p class="mt-4 text-lg font-black text-[#18542A]">
                            Belum Ada Rekomendasi
                        </p>

                        <p class="mt-2 text-sm leading-6 text-[#6B5F46]">
                            Lengkapi filter agar sistem dapat memberikan rekomendasi menu terbaik.
                        </p>

                    </div>
                @endif

            </div>
        </article>
    @endforeach
</div>
