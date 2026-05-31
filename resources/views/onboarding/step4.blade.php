@extends('onboarding.layout')
@php $currentStep = 4; @endphp

@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8" x-data="{ hasMedical: '{{ old('has_medical_need', 'no') }}' }">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mt-3">Kebutuhan Khusus</h2>
        <p class="text-gray-500 mt-2">Apakah kamu memiliki kebutuhan makanan khusus atau preferensi bahan?</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.4') }}">
        @csrf
        <input type="hidden" name="has_medical_need" value="no">

        <div class="space-y-5">
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah kamu memiliki kebutuhan makanan khusus?</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-center gap-3 p-4 border-2 rounded-xl text-left transition" :class="hasMedical==='yes' ? 'border-ng-orange bg-orange-50' : 'border-gray-200 hover:border-ng-orange'">
                        <input type="radio" name="has_medical_need" value="yes" class="sr-only" @change="hasMedical='yes'" {{ old('has_medical_need', 'no') === 'yes' ? 'checked' : '' }}>
                        <span class="font-semibold text-gray-700">Ya, ada</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 p-4 border-2 rounded-xl text-left transition" :class="hasMedical==='no' ? 'border-ng-orange bg-orange-50' : 'border-gray-200 hover:border-ng-orange'">
                        <input type="radio" name="has_medical_need" value="no" class="sr-only" @change="hasMedical='no'" {{ old('has_medical_need', 'no') === 'no' ? 'checked' : '' }}>
                        <span class="font-semibold text-gray-700">Tidak ada</span>
                    </label>
                </div>
            </div>

        <div x-show="hasMedical==='yes'" x-transition class="space-y-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Makanan / bahan</label>
                    <input type="text" name="food_item" class="input-field" placeholder="Cth: telur, susu, wortel" :required="hasMedical==='yes'">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Jumlah</label>
                    <input type="number" name="quantity" class="input-field" placeholder="2" min="1" step="1" inputmode="numeric" value="{{ old('quantity') }}" :required="hasMedical==='yes'">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Satuan</label>
                    <select name="unit" class="input-field" :required="hasMedical==='yes'">
                        <option value="">-- Pilih Satuan --</option>
                        <option value="butir" {{ old('unit') === 'butir' ? 'selected' : '' }}>Butir</option>
                        <option value="pcs" {{ old('unit') === 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="porsi" {{ old('unit') === 'porsi' ? 'selected' : '' }}>Porsi</option>
                        <option value="buah" {{ old('unit') === 'buah' ? 'selected' : '' }}>Buah</option>
                        <option value="gram" {{ old('unit') === 'gram' ? 'selected' : '' }}>Gram</option>
                        <option value="ml" {{ old('unit') === 'ml' ? 'selected' : '' }}>Ml</option>
                        <option value="gelas" {{ old('unit') === 'gelas' ? 'selected' : '' }}>Gelas</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Frekuensi</label>
                    <select name="duration_type" class="input-field" :required="hasMedical==='yes'">
                        <option value="">-- Pilih Durasi --</option>
                        <option value="daily" {{ old('duration_type') === 'daily' ? 'selected' : '' }}>Setiap hari</option>
                        <option value="weekly" {{ old('duration_type') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="yearly" {{ old('duration_type') === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        <option value="forever" {{ old('duration_type') === 'forever' ? 'selected' : '' }}>Selamanya</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 3]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">Lanjut →</button>
        </div>
    </form>
</div>
@endsection