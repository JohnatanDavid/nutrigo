<div id="food-modal-overlay" class="fixed inset-0 z-60 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">

    <div class="w-full max-w-xl rounded-3xl bg-white shadow-[0_20px_50px_rgba(0,0,0,0.15)]">

        {{-- Header --}}
        <div class="border-b border-[#F3E8CC] p-5">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <h3 class="text-2xl font-black text-[#18542A]">
                        {{ $food->name }}
                    </h3>

                    <div class="mt-3 flex flex-wrap gap-2">

                        <span class="rounded-full bg-[#18542A] px-3 py-1 text-xs font-bold text-white">
                            {{ $food->origin ?? 'N/A' }}
                        </span>

                        <span class="rounded-full bg-[#FFC926] px-3 py-1 text-xs font-black text-[#18542A]">
                            {{ $food->calories }} kkal
                        </span>

                    </div>
                </div>

                <button
                    class="close-food-modal rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>

            </div>

        </div>

        {{-- Content --}}
        <div class="space-y-5 p-5">

            {{-- Komposisi --}}
            <div>

                <h4 class="mb-2 text-sm font-bold uppercase tracking-wide text-[#9ABC05]">
                    Komposisi
                </h4>

                <div class="rounded-2xl bg-[#F3E8CC] p-4 text-sm leading-7 text-[#5f5647]">
                    {{ $food->composition }}
                </div>

            </div>

            {{-- Nutrisi --}}
            <div>

                <h4 class="mb-3 text-sm font-bold uppercase tracking-wide text-[#18542A]">
                    Informasi Nutrisi
                </h4>

                <div class="grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-[#FFC926] p-3 text-center shadow-sm">
                        <div class="text-lg font-black text-[#18542A]">
                            {{ $food->calories }}
                        </div>
                        <div class="text-[11px] font-semibold text-[#18542A]/80">
                            Kalori
                        </div>
                    </div>

                    <div class="rounded-xl bg-[#F96015] p-3 text-center shadow-sm">
                        <div class="text-lg font-black text-white">
                            {{ $food->proteins }}
                        </div>
                        <div class="text-[11px] font-semibold text-white/80">
                            Protein
                        </div>
                    </div>

                    <div class="rounded-xl bg-[#9ABC05] p-3 text-center shadow-sm">
                        <div class="text-lg font-black text-[#18542A]">
                            {{ $food->carbohydrates ?? '—' }}
                        </div>
                        <div class="text-[11px] font-semibold text-[#18542A]/80">
                            Karbohidrat
                        </div>
                    </div>

                    <div class="rounded-xl bg-[#18542A] p-3 text-center shadow-sm">
                        <div class="text-lg font-black text-white">
                            {{ $food->fat ?? '—' }}
                        </div>
                        <div class="text-[11px] font-semibold text-white/80">
                            Lemak
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
