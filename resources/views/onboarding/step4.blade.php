@extends('onboarding.layout')
@php $currentStep = 4; @endphp

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8" data-onboarding-switcher>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 mt-3">Kebutuhan Khusus</h2>
        <p class="text-base text-gray-600 mt-2">Apakah kamu memiliki kebutuhan makanan khusus atau preferensi bahan?</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.4') }}">
        @csrf

        <input type="hidden" name="has_medical_need" value="{{ old('has_medical_need', 'no') }}" data-choice-value>

        <div class="space-y-5">
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah kamu memiliki kebutuhan makanan khusus?</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button type="button" class="card-selector w-full text-left medical-switcher" data-choice-button="yes">
                        <div class="font-semibold">Ya</div>
                        <div class="text-sm text-gray-600 mt-1">Saya memiliki kebutuhan makanan khusus</div>
                    </button>
                    <button type="button" class="card-selector w-full text-left medical-switcher" data-choice-button="no">
                        <div class="font-semibold">Tidak</div>
                        <div class="text-sm text-gray-600 mt-1">Saya tidak memiliki kebutuhan khusus</div>
                    </button>
                </div>
            </div>
            <div data-choice-detail></div>
            <template data-choice-template>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="sm:col-span-2">
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Bahan Makanan</label>
                            <input type="text" name="food_item" class="input-field" placeholder="Contoh: Telur" value="{{ old('food_item') }}">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Kuantitas</label>
                            <input type="number" name="quantity" class="input-field" placeholder="Contoh: 2" min="1" step="1" inputmode="numeric" value="{{ old('quantity') }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Satuan</label>
                            <select name="unit" class="input-field">
                                <option value="">-- Pilih Satuan --</option>
                                <option value="butir" {{ old('unit') === 'butir' ? 'selected' : '' }}>Butir</option>
                                <option value="satuan" {{ old('unit') === 'satuan' ? 'selected' : '' }}>Satuan</option>
                                <option value="porsi" {{ old('unit') === 'porsi' ? 'selected' : '' }}>Porsi</option>
                                <option value="ml" {{ old('unit') === 'ml' ? 'selected' : '' }}>ml</option>
                                <option value="gram" {{ old('unit') === 'gram' ? 'selected' : '' }}>Gram</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Frekuensi</label>
                            <select name="duration_type" class="input-field">
                                <option value="">-- Pilih Frekuensi --</option>
                                <option value="daily" {{ old('duration_type') === 'daily' ? 'selected' : '' }}>Setiap Hari</option>
                                <option value="weekly" {{ old('duration_type') === 'weekly' ? 'selected' : '' }}>Setiap Minggu</option>
                                <option value="monthly" {{ old('duration_type') === 'monthly' ? 'selected' : '' }}>Setiap Bulan</option>
                                <option value="yearly" {{ old('duration_type') === 'yearly' ? 'selected' : '' }}>Setiap Tahun</option>
                                <option value="forever" {{ old('duration_type') === 'forever' ? 'selected' : '' }}>Selamanya</option>
                            </select>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 3]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">Selanjutnya →</button>
        </div>
    </form>
</div>
<script>
    (function () {
        function clearDetailFields(detail) {
            detail.querySelectorAll('input[type="checkbox"]').forEach((input) => {
                input.checked = false;
            });

            detail.querySelectorAll('input[type="text"], input[type="number"]').forEach((input) => {
                input.value = '';
            });

            detail.querySelectorAll('select').forEach((select) => {
                select.selectedIndex = 0;
            });
        }

        function applyChoice(section, value) {
            const hiddenInput = section.querySelector('[data-choice-value]');
            const buttons = section.querySelectorAll('[data-choice-button]');
            const detail = section.querySelector('[data-choice-detail]');
            const template = section.querySelector('[data-choice-template]');

            if (hiddenInput) {
                hiddenInput.value = value;
            }

            buttons.forEach((button) => {
                const active = button.dataset.choiceButton === value;
                button.classList.toggle('card-selector-active', active);
                button.classList.toggle('card-selector-inactive', !active);
            });

            if (!detail || !template) {
                return;
            }

            const showDetail = value === 'yes';

            if (showDetail) {
                if (!detail.childElementCount) {
                    detail.appendChild(template.content.cloneNode(true));
                }
            } else {
                clearDetailFields(detail);
                detail.innerHTML = '';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-onboarding-switcher]').forEach((section) => {
                const hiddenInput = section.querySelector('[data-choice-value]');
                const initialValue = hiddenInput && hiddenInput.value === 'yes' ? 'yes' : 'no';

                applyChoice(section, initialValue);

                section.addEventListener('click', (event) => {
                    const button = event.target.closest('[data-choice-button]');

                    if (!button || !section.contains(button)) {
                        return;
                    }

                    applyChoice(section, button.dataset.choiceButton || 'no');
                });
            });
        });
    })();
</script>
@endsection