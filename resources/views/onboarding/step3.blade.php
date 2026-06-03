@extends('onboarding.layout')
@php $currentStep = 3; @endphp

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8" data-onboarding-switcher>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 mt-3">Alergi Makanan</h2>
        <p class="text-base text-gray-600 mt-2">Pilih bahan yang menjadi alergimu agar rekomendasi aman.</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.3') }}">
        @csrf

        <input type="hidden" name="has_allergy" value="{{ old('has_allergy', 'no') }}" data-choice-value>

        <div class="space-y-5">
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah kamu memiliki alergi makanan?</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button type="button" class="card-selector w-full text-left allergy-switcher" data-choice-button="yes">
                        <div class="font-semibold">Ya</div>
                        <div class="text-sm text-gray-600 mt-1">Saya punya alergi makanan</div>
                    </button>
                    <button type="button" class="card-selector w-full text-left allergy-switcher" data-choice-button="no">
                        <div class="font-semibold">Tidak</div>
                        <div class="text-sm text-gray-600 mt-1">Saya tidak memiliki alergi makanan</div>
                    </button>
                </div>
            </div>

            <div data-choice-detail></div>
            <template data-choice-template>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Pilih alergenmu</label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach($compositionOptions as $comp)
                                <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition border-gray-200 hover:border-ng-orange">
                                    <input type="checkbox" name="allergens[]" value="{{ $comp }}" {{ in_array($comp, old('allergens', [])) ? 'checked' : '' }} class="form-checkbox">
                                    <span class="font-semibold text-gray-700">{{ $comp }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Alergi lainnya (opsional)</label>
                        <input type="text" name="custom_allergy" class="input-field" placeholder="Cth: durian, pete..." value="{{ old('custom_allergy') }}">
                    </div>
                </div>
            </template>
        </div>

        @include('onboarding._errors')

        <div class="mt-8 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 2]) }}" class="btn-outline">← Kembali</a>
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
                button.setAttribute('aria-pressed', active ? 'true' : 'false');
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

        function initSection(section) {
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
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-onboarding-switcher]').forEach(initSection);
        });
    })();
</script>
@endsection