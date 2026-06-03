@extends('onboarding.layout')
@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8" data-onboarding-step2>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">Preferensi Anda</h2>
        <p class="text-base text-gray-600 mt-2">Tentukan alergi dan kebutuhan khusus supaya rekomendasi lebih sesuai</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.2') }}">
        @csrf

        <input type="hidden" name="has_allergy" value="{{ old('has_allergy', 'no') }}" data-choice-value="allergy">
        <input type="hidden" name="has_medical_need" value="{{ old('has_medical_need', 'no') }}" data-choice-value="medical">

        <div class="space-y-6">
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah Anda memiliki alergi makanan?</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button type="button" class="card-selector w-full text-left allergy-switcher" data-choice-button="allergy-yes">
                        <div class="font-semibold">Ya</div>
                        <div class="text-sm text-gray-600 mt-1">Saya punya alergi makanan</div>
                    </button>
                    <button type="button" class="card-selector w-full text-left allergy-switcher" data-choice-button="allergy-no">
                        <div class="font-semibold">Tidak</div>
                        <div class="text-sm text-gray-600 mt-1">Saya tidak memiliki alergi makanan</div>
                    </button>
                </div>
            </div>

            <div data-choice-detail="allergy"></div>
            <template data-choice-template="allergy">
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Pilih alergi (opsional)</label>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                        @foreach($compositionOptions ?? [] as $option)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="allergens[]" value="{{ $option }}" {{ is_array(old('allergens')) && in_array($option, old('allergens')) ? 'checked' : '' }} class="form-checkbox">
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <label class="text-sm font-semibold text-gray-700 block">Alergi lain (jika tidak ada di daftar)</label>
                        <input type="text" name="custom_allergy" value="{{ old('custom_allergy') }}" class="input-field mt-1" placeholder="Contoh: Alergi X">
                    </div>
                </div>
            </template>

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah Anda memiliki kebutuhan makanan khusus?</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-3">
                    <button type="button" class="card-selector w-full text-left medical-switcher" data-choice-button="medical-yes">
                        <div class="font-semibold">Ya</div>
                        <div class="text-sm text-gray-600 mt-1">Saya memiliki kebutuhan makanan khusus</div>
                    </button>
                    <button type="button" class="card-selector w-full text-left medical-switcher" data-choice-button="medical-no">
                        <div class="font-semibold">Tidak</div>
                        <div class="text-sm text-gray-600 mt-1">Saya tidak memiliki kebutuhan khusus</div>
                    </button>
                </div>
            </div>

            <div data-choice-detail="medical"></div>
            <template data-choice-template="medical">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium">Bahan Makanan</label>
                        <input type="text" name="food_item" value="{{ old('food_item') }}" class="input-field mt-1" placeholder="Contoh: Telur">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Kuantitas</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" class="input-field mt-1" placeholder="Contoh: 2">
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium">Satuan</label>
                        <select name="unit" class="input-field mt-1">
                            <option value="">-- Pilih Satuan --</option>
                            <option value="butir" {{ old('unit')=='butir' ? 'selected' : '' }}>Butir</option>
                            <option value="satuan" {{ old('unit')=='satuan' ? 'selected' : '' }}>Satuan</option>
                            <option value="porsi" {{ old('unit')=='porsi' ? 'selected' : '' }}>Porsi</option>
                            <option value="gram" {{ old('unit')=='gram' ? 'selected' : '' }}>Gram</option>
                            <option value="ml" {{ old('unit')=='ml' ? 'selected' : '' }}>ml</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Frekuensi</label>
                        <select name="duration_type" class="input-field mt-1">
                            <option value="">-- Pilih Frekuensi --</option>
                            <option value="daily" {{ old('duration_type')=='daily' ? 'selected' : '' }}>Setiap Hari</option>
                            <option value="weekly" {{ old('duration_type')=='weekly' ? 'selected' : '' }}>Setiap Minggu</option>
                            <option value="monthly" {{ old('duration_type')=='monthly' ? 'selected' : '' }}>Setiap Bulan</option>
                            <option value="yearly" {{ old('duration_type')=='yearly' ? 'selected' : '' }}>Setiap Tahun</option>
                            <option value="forever" {{ old('duration_type')=='forever' ? 'selected' : '' }}>Selamanya</option>
                        </select>
                    </div>
                </div>
            </template>
            </div>

            @include('onboarding._errors')

        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 1]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">Simpan & Selesai</button>
        </div>
    </form>
</div>

<script>
    (function () {
        function clearDetailFields(detail) {
            detail.querySelectorAll('input[type="checkbox"]').forEach((input) => input.checked = false);
            detail.querySelectorAll('input[type="text"], input[type="number"]').forEach((input) => input.value = '');
            detail.querySelectorAll('select').forEach((select) => select.selectedIndex = 0);
        }

        function applyChoice(section, group, value) {
            const hiddenInput = section.querySelector(`[data-choice-value="${group}"]`);
            const buttons = section.querySelectorAll(`[data-choice-button^="${group}-"]`);
            const detail = section.querySelector(`[data-choice-detail="${group}"]`);
            const template = section.querySelector(`[data-choice-template="${group}"]`);

            if (hiddenInput) {
                hiddenInput.value = value;
            }

            buttons.forEach((button) => {
                const active = button.dataset.choiceButton === `${group}-${value}`;
                button.classList.toggle('card-selector-active', active);
                button.classList.toggle('card-selector-inactive', !active);
            });

            if (!detail || !template) return;

            if (value === 'yes') {
                if (!detail.childElementCount) {
                    detail.appendChild(template.content.cloneNode(true));
                }
            } else {
                clearDetailFields(detail);
                detail.innerHTML = '';
            }
        }

        function init() {
            document.querySelectorAll('[data-onboarding-step2]').forEach((section) => {
                const allergyValue = section.querySelector('[data-choice-value="allergy"]')?.value === 'yes' ? 'yes' : 'no';
                const medicalValue = section.querySelector('[data-choice-value="medical"]')?.value === 'yes' ? 'yes' : 'no';

                applyChoice(section, 'allergy', allergyValue);
                applyChoice(section, 'medical', medicalValue);

                section.addEventListener('click', (event) => {
                    const button = event.target.closest('[data-choice-button]');
                    if (!button || !section.contains(button)) return;

                    const [group, value] = button.dataset.choiceButton.split('-');
                    applyChoice(section, group, value || 'no');
                });
            });
        }

        document.addEventListener('DOMContentLoaded', init);
    })();
</script>

@endsection
