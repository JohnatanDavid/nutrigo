@extends('onboarding.layout')
@php $currentStep = 3; @endphp

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8" x-data="{ hasAllergy: '{{ old('has_allergy', 'no') }}' }">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mt-3">Alergi Makanan</h2>
        <p class="text-gray-500 mt-2">Pilih bahan yang menjadi alergimu agar rekomendasi aman.</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.3') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah kamu memiliki alergi makanan?</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-center gap-3 p-4 border-2 rounded-xl text-left transition" :class="hasAllergy==='yes' ? 'border-ng-orange bg-orange-50' : 'border-gray-200 hover:border-ng-orange'">
                        <input type="radio" name="has_allergy" value="yes" class="sr-only" @change="hasAllergy='yes'" {{ old('has_allergy', 'no') === 'yes' ? 'checked' : '' }}>
                        <span class="font-semibold text-gray-700">Ya, ada</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 p-4 border-2 rounded-xl text-left transition" :class="hasAllergy==='no' ? 'border-ng-orange bg-orange-50' : 'border-gray-200 hover:border-ng-orange'">
                        <input type="radio" name="has_allergy" value="no" class="sr-only" @change="hasAllergy='no'" {{ old('has_allergy', 'no') === 'no' ? 'checked' : '' }}>
                        <span class="font-semibold text-gray-700">Tidak ada</span>
                    </label>
                </div>
            </div>

            <div x-show="hasAllergy==='yes'" x-transition class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Pilih alergenmu</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($compositionOptions as $comp)
                            <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition border-gray-200 hover:border-ng-orange">
                                <input type="checkbox" name="allergens[]" value="{{ $comp }}" {{ in_array($comp, old('allergens', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-ng-orange focus:ring-ng-orange">
                                <span class="font-semibold text-gray-700">{{ $comp }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Alergi lainnya (opsional)</label>
                    <input type="text" name="custom_allergy" class="input-field" placeholder="Cth: durian, pete...">
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 2]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">Lanjut →</button>
        </div>
    </form>
</div>
@endsection