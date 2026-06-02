@php
    $items = [$menu['breakfast'] ?? null, $menu['lunch'] ?? null, $menu['dinner'] ?? null];
    $regions = ['JAWA TIMUR','JAWA TIMUR','JAWA TIMUR'];
@endphp

<div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
    @foreach($items as $index => $card)
        <article class="overflow-hidden rounded-2xl bg-[#fff4cb] shadow-[0_18px_40px_rgba(20,40,20,0.08)] ring-1 ring-black/5 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_25px_50px_rgba(24,84,45,0.15)]">
            <div class="relative">
                <div class="h-44 w-full overflow-hidden">
                    <img src="{{ $card?->image ? asset($card->image) : asset('assets/salad-sketsa 1.png') }}" alt="{{ $card?->name ?? 'Menu' }}" class="w-full h-full object-cover">
                </div>
                <div class="absolute left-4 top-4 rounded-full bg-[#1d5b2f] px-3 py-1 text-xs font-bold text-white">{{ $regions[$index] ?? 'JAWA TIMUR' }}</div>
                <div class="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-bold text-[#17311f]">{{ $card?->calories ?? '—' }} kcal</div>
            </div>

            <div class="p-6">
                @if($card)
                    <p class="text-[25px] font-black text-[#18542D] leading-tight">{{ $card->name }}</p>
                   <p class="mt-3 text-sm leading-6 text-[#75684f]">{{ $card->composition ?: 'Rekomendasi menu sehat yang sesuai dengan kebutuhanmu.' }}</p>

                    <div class="mt-5 flex items-center gap-3">
                        <a href="#" class="open-food-detail inline-flex items-center gap-2 rounded-full border border-[#1d5b2f] px-5 py-3 text-sm font-bold text-[#1d5b2f] transition hover:bg-white" data-food-id="{{ $card->id }}" data-meal-type="{{ ['breakfast','lunch','dinner'][$index] ?? 'breakfast' }}">Detail</a>
                    </div>

                @else
                    <div class="mt-2 rounded-lg bg-white/60 p-4">
                        <p class="text-lg font-black text-[#245432]">Belum ada rekomendasi</p>
                        <p class="mt-2 text-sm leading-6 text-[#6b5f46]">Lengkapi filter agar menu bisa dibuat.</p>
                    </div>
                @endif
            </div>
        </article>
    @endforeach
</div>
