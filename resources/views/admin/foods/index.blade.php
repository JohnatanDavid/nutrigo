    @extends('layouts.admin')
    @section('title','Kelola Data Makanan')
    @section('page-title','Kelola Data Makanan')

    @section('content')
    <div class="mb-6">
        <div class="bg-gradient-to-r from-ng-orange to-ng-yellow rounded-2xl p-6 text-white shadow-sm">
            <h2 class="text-2xl font-bold">Kelola Data Makanan</h2>
            <p class="text-sm opacity-90 mt-1">Atur database makanan untuk sistem rekomendasi NutriGo</p>
        </div>
    </div>

    <section class="mb-5 rounded-2xl border border-[#eadfce] bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
            @php
                $hasActiveFilter = request()->filled('search') || request()->filled('meal_type');
            @endphp
            <form id="foods-filter-form" method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="flex-[1.2]">
                    <div class="relative">
                        <input
                            id="foods-search-input"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama makanan..."
                            class="w-full rounded-full border border-[#f3dede] bg-[#fbefef] px-4 py-3 pr-12 text-sm text-[#8d8d8d] outline-none ring-[#f96015] transition focus:ring-2 lg:max-w-xl"
                        >
                        <button type="button" id="clear-search" class="absolute right-1 top-1/2 hidden h-6 w-6 -translate-y-1/2 items-center justify-center rounded-full border border-[#e7dfd5] bg-white text-[#a59c94] transition hover:border-[#d52518] hover:text-[#d52518]" aria-label="Bersihkan pencarian">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6L6 18"></path>
                                <path d="M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 lg:flex-nowrap">
                    <div class="relative">
                        <select name="meal_type" class="rounded-full border border-[#e7dfd5] bg-[#f7f0e4] py-3 pl-4 pr-28 text-sm font-medium text-[#6b7280] outline-none ring-[#9abc05] focus:ring-2">
                            <option value="">Semua Kategori</option>
                            @foreach(['breakfast'=>'Sarapan','lunch'=>'Makan Siang','dinner'=>'Makan Malam','snack'=>'Snack'] as $v=>$l)
                                <option value="{{ $v }}" {{ request('meal_type') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>

                        <div class="absolute inset-y-0 right-1 flex items-center gap-1.5">
                            <button type="submit" class="rounded-full bg-[#f96015] px-3 py-1.5 text-xs font-bold text-white transition hover:bg-[#e55310]">
                                Filter
                            </button>

                            @if($hasActiveFilter)
                                <a href="{{ route('admin.foods.index') }}" class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-[#e7dfd5] bg-white text-[#a59c94] transition hover:border-[#d52518] hover:text-[#d52518]" title="Hapus filter">
                                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 6L6 18"></path>
                                        <path d="M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
            <div class="lg:ml-auto">
                <button id="open-add-food" class="rounded-full bg-[#185420] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#123b18]">
                    + Tambah Makanan
                </button>
            </div>
        </div>
    </section>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-600">
                        <th class="px-6 py-4">Nama Makanan</th>
                        <th class="px-6 py-4">Kalori (kkal)</th>
                        <th class="px-6 py-4">Protein (g)</th>
                        <th class="px-6 py-4">Lemak (g)</th>
                        <th class="px-6 py-4">Karbo (g)</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @foreach($foods as $food)
                        <?php $calPct = min(100, ($food->calories ?? 0) / 300 * 100); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-6 font-medium text-gray-800">
                                <div class="flex items-center gap-3">
                                    <div class="h-11 w-11 rounded-xl overflow-hidden bg-ng-cream flex items-center justify-center text-xs text-gray-400 border border-gray-100">
                                        @if($food->image_url)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($food->image_url) }}" alt="{{ $food->name }}" class="h-full w-full object-cover">
                                        @else
                                            No img
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $food->name }}</div>
                                        <div class="text-xs text-gray-400">{{ ucfirst($food->meal_type) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-40 bg-ng-cream h-2 rounded-full overflow-hidden">
                                        <div class="h-2 rounded-full bg-ng-orange" style="width: {{ $calPct }}%"></div>
                                    </div>
                                    <div class="text-sm text-gray-700">{{ $food->calories }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">{{ $food->proteins ?? 0 }}</td>
                            <td class="px-6 py-6 text-center">{{ $food->fat ?? 0 }}</td>
                            <td class="px-6 py-6 text-center">{{ $food->carbohydrate ?? 0 }}</td>
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.foods.edit', $food) }}"
                                       class="inline-flex items-center gap-2 rounded-full border border-ng-dark-green/20 bg-ng-cream px-4 py-2 text-sm font-semibold text-ng-dark-green shadow-sm transition hover:-translate-y-0.5 hover:shadow-md cursor-pointer">
                                        <span>Edit</span>
                                        <span class="text-xs opacity-75">↗</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.foods.destroy', $food) }}" onsubmit="return confirm('Hapus makanan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md cursor-pointer">
                                            <span>Hapus</span>
                                            <span class="text-xs opacity-75">×</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
            <div>Menampilkan {{ $foods->count() }} dari {{ $foods->total() }} data</div>
            <div class="flex items-center gap-2">{{ $foods->withQueryString()->links() }}</div>
        </div>
    </div>

    <!-- Drawer: Tambah Makanan -->
    <div id="food-drawer" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-30" id="drawer-backdrop"></div>
        <div class="absolute right-0 top-0 h-full w-full md:w-[460px] bg-white shadow-xl p-6 overflow-auto">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Detail Makanan</h3>
                    <p class="text-sm text-gray-500">Lengkapi informasi nutrisi makanan</p>
                </div>
                <button id="close-drawer" class="text-gray-400">✕</button>
            </div>

            <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Foto Makanan</label>
                    <div class="border-2 border-dashed border-ng-cream rounded-lg p-6 text-center">
                        <input type="file" name="photo" accept="image/*" class="mx-auto text-sm" />
                        <div class="text-sm text-gray-500 mt-2">Tarik gambar ke sini atau pilih file</div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">Nama Makanan</label>
                    <input name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Kalori (kkal)</label>
                        <input name="calories" value="{{ old('calories') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Protein (g)</label>
                        <input name="proteins" value="{{ old('proteins') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Lemak (g)</label>
                        <input name="fat" value="{{ old('fat') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Karbohidrat (g)</label>
                        <input name="carbohydrate" value="{{ old('carbohydrate') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">Alergen</label>
                    <input name="composition" value="{{ old('composition') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" placeholder="Contoh: Seafood, Kacang" />
                    <p class="mt-1 text-xs text-gray-500">Field ini dipakai untuk filter alergi saat rekomendasi menu.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Asal</label>
                        <input name="origin" value="{{ old('origin') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Region</label>
                        <input name="region" value="{{ old('region') }}" class="w-full rounded-lg border border-ng-cream px-4 py-3" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">Tipe Makanan</label>
                    <select name="meal_type" class="w-full rounded-lg border border-ng-cream px-4 py-3">
                        <option value="breakfast" {{ old('meal_type', 'lunch') === 'breakfast' ? 'selected' : '' }}>Sarapan</option>
                        <option value="lunch" {{ old('meal_type', 'lunch') === 'lunch' ? 'selected' : '' }}>Makan Siang</option>
                        <option value="dinner" {{ old('meal_type', 'lunch') === 'dinner' ? 'selected' : '' }}>Makan Malam</option>
                        <option value="snack" {{ old('meal_type', 'lunch') === 'snack' ? 'selected' : '' }}>Snack</option>
                    </select>
                </div>

                <div class="flex gap-3 mt-4">
                    <button type="submit" class="flex-1 px-4 py-3 rounded-lg bg-ng-dark-green text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg cursor-pointer">Simpan Data</button>
                    <button type="button" id="cancel-drawer" class="flex-1 px-4 py-3 rounded-lg border">Batal</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const openBtn = document.getElementById('open-add-food');
        const drawer = document.getElementById('food-drawer');
        const closeBtn = document.getElementById('close-drawer');
        const backdrop = document.getElementById('drawer-backdrop');
        const cancelBtn = document.getElementById('cancel-drawer');

        function openDrawer(){ drawer.classList.remove('hidden'); }
        function closeDrawer(){ drawer.classList.add('hidden'); }

        const filterForm = document.getElementById('foods-filter-form');
        const clearSearchBtn = document.getElementById('clear-search');
        const searchInput = document.getElementById('foods-search-input');

        function syncSearchClearButton() {
            if (!clearSearchBtn || !searchInput) return;
            clearSearchBtn.classList.toggle('hidden', !searchInput.value.trim());
        }

        openBtn?.addEventListener('click', e=>{ openDrawer(); });
        closeBtn?.addEventListener('click', e=>{ closeDrawer(); });
        backdrop?.addEventListener('click', e=>{ closeDrawer(); });
        cancelBtn?.addEventListener('click', e=>{ closeDrawer(); });

        searchInput?.addEventListener('input', syncSearchClearButton);
        clearSearchBtn?.addEventListener('click', () => {
            if (searchInput) {
                searchInput.value = '';
            }
            syncSearchClearButton();
            filterForm?.submit();
        });

        syncSearchClearButton();

        if (window.location.search.includes('search=')) {
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            window.history.replaceState({}, '', url.pathname + (url.search ? `?${url.searchParams.toString()}` : ''));
        }

        // Re-open drawer if validation failed
        @if($errors->any())
            openDrawer();
        @endif
    </script>
    @endpush

    @endsection