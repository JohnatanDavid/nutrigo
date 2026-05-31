@extends('onboarding.layout')
@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Preferensi Anda</h2>
        <p class="text-gray-500 mt-2">Tentukan alergi dan kebutuhan khusus supaya rekomendasi lebih sesuai</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.2') }}">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah Anda memiliki alergi makanan?</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center"><input type="radio" name="has_allergy" value="yes" {{ old('has_allergy') === 'yes' ? 'checked' : '' }} class="form-radio" required><span class="ml-2">Ya</span></label>
                    <label class="inline-flex items-center"><input type="radio" name="has_allergy" value="no" {{ old('has_allergy') === 'no' ? 'checked' : '' }} class="form-radio" required><span class="ml-2">Tidak</span></label>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Pilih alergi (opsional)</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($compositionOptions ?? [] as $option)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="allergens[]" value="{{ $option }}" {{ is_array(old('allergens')) && in_array($option, old('allergens')) ? 'checked' : '' }} class="form-checkbox">
                            <span class="ml-2">{{ $option }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-3">
                    <label class="text-sm font-semibold text-gray-700 block">Alergi lain (jika tidak ada di daftar)</label>
                    <input type="text" name="custom_allergy" value="{{ old('custom_allergy') }}" class="input-field mt-1" placeholder="Contoh: Alergi X">
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Apakah Anda memiliki kebutuhan makanan khusus?</label>
                <div class="flex items-center gap-4 mb-3">
                    <label class="inline-flex items-center"><input type="radio" name="has_medical_need" value="yes" {{ old('has_medical_need') === 'yes' ? 'checked' : '' }} class="form-radio" required><span class="ml-2">Ya</span></label>
                    <label class="inline-flex items-center"><input type="radio" name="has_medical_need" value="no" {{ old('has_medical_need') === 'no' ? 'checked' : '' }} class="form-radio" required><span class="ml-2">Tidak</span></label>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm font-medium">Nama makanan / suplemen</label>
                        <input type="text" name="food_item" value="{{ old('food_item') }}" class="input-field mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Kuantitas</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" class="input-field mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Satuan</label>
                        <input type="text" name="unit" value="{{ old('unit') }}" class="input-field mt-1">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="text-sm font-medium">Frekuensi</label>
                    <select name="duration_type" class="input-field mt-1">
                        <option value="daily" {{ old('duration_type')=='daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ old('duration_type')=='weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="yearly" {{ old('duration_type')=='yearly' ? 'selected' : '' }}>Tahunan</option>
                        <option value="forever" {{ old('duration_type')=='forever' ? 'selected' : '' }}>Selamanya</option>
                    </select>
                </div>
            </div>

            @if($errors->any())
                <div class="mt-2 bg-red-50 rounded-xl p-3">
                    @foreach($errors->all() as $e)<p class="text-red-600 text-sm">⚠️ {{ $e }}</p>@endforeach
                </div>
            @endif

        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 1]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">Simpan & Selesai</button>
        </div>
    </form>
</div>

@endsection
