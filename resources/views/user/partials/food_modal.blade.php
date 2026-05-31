<div id="food-modal-overlay" class="fixed inset-0 z-60 flex items-center justify-center bg-black/50">
    <div class="w-full max-w-4xl rounded-2xl bg-white p-6 shadow-lg">
        <div class="flex items-start gap-6">
            <div class="w-1/2">
                <img src="{{ $food->image ? asset($food->image) : asset('assets/salad-sketsa 1.png') }}" alt="{{ $food->name }}" class="w-full h-64 object-cover rounded-lg">
                <div class="mt-3 flex gap-2">
                    <span class="rounded-full bg-[#1d5b2f] px-3 py-1 text-xs font-bold text-white">{{ $food->origin ?? 'N/A' }}</span>
                    <span class="rounded-full bg-[#ffd953] px-3 py-1 text-xs font-bold text-[#17311f]">{{ $food->calories }} kkal</span>
                </div>
            </div>
            <div class="w-1/2">
                <h3 class="text-2xl font-black text-[#17311f]">{{ $food->name }}</h3>
                <div class="mt-3 text-sm text-[#6b5f46]">{{ $food->composition }}</div>
                <div class="mt-4 grid grid-cols-4 gap-2 bg-[#fff4e0] p-3 rounded-lg">
                    <div class="text-center">
                        <div class="font-bold text-lg">{{ $food->calories }}</div>
                        <div class="text-xs">Kalori</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-lg">{{ $food->proteins }}</div>
                        <div class="text-xs">Protein</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-lg">{{ $food->carbohydrates ?? '—' }}</div>
                        <div class="text-xs">Karbo</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-lg">{{ $food->fat ?? '—' }}</div>
                        <div class="text-xs">Lemak</div>
                    </div>
                </div>

                <div class="mt-4 flex gap-3">
                    <button class="select-from-modal flex-1 rounded-full bg-[#da2d1c] px-4 py-3 text-sm font-bold text-white" data-food-id="{{ $food->id }}">Pilih Menu Ini</button>
                    <button class="close-food-modal rounded-full border border-[#d9c8a1] px-4 py-3 text-sm font-bold">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
