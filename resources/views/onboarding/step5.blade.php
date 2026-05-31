@extends('onboarding.layout')
@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8">
    <div class="text-center mb-8">
        <span class="text-5xl">🏃</span>
        <h2 class="text-2xl font-bold text-gray-800 mt-3">Data kesehatan</h2>
        <p class="text-gray-500 mt-2">Lengkapi untuk hitung kebutuhan kalori</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.5') }}">
        @csrf
        <div class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Usia</label>
                    <input type="number" name="age" value="{{ old('age') }}"
                           class="input-field" placeholder="21" min="1" max="120" required>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Jenis Kelamin</label>
                    <select name="gender" class="input-field" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Tinggi Badan (cm)</label>
                    <input type="number" name="height_cm" value="{{ old('height_cm') }}"
                           class="input-field" placeholder="165" min="50" max="300" step="0.1" required>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Berat Badan (kg)</label>
                    <input type="number" name="weight_kg" value="{{ old('weight_kg') }}"
                           class="input-field" placeholder="60" min="10" max="300" step="0.1" required>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Level Aktivitas Harianmu</label>
                <div class="space-y-2">
                    @foreach([
                        ['value'=>'sedentary',  'label'=>'Ringan (duduk, kerja laptop)', 'icon'=>'💻'],
                        ['value'=>'light',      'label'=>'Sedikit aktif (jalan kaki ringan)', 'icon'=>'🚶'],
                        ['value'=>'moderate',   'label'=>'Cukup aktif (olahraga 3-5x/minggu)', 'icon'=>'🏃'],
                        ['value'=>'active',     'label'=>'Aktif (olahraga intensif)', 'icon'=>'⚡'],
                        ['value'=>'very_active','label'=>'Sangat aktif (kerja fisik/gym rutin)', 'icon'=>'🏋️'],
                    ] as $opt)
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer {{ old('activity_level') == $opt['value'] ? 'border-ng-orange bg-orange-50' : 'border-gray-200 hover:border-ng-orange' }}">
                            <input type="radio" name="activity_level" value="{{ $opt['value'] }}" class="mr-3" {{ old('activity_level') == $opt['value'] ? 'checked' : '' }} required>
                            <span class="text-2xl mr-3">{{ $opt['icon'] }}</span>
                            <span class="text-gray-700 font-semibold">{{ $opt['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="mt-4 bg-red-50 rounded-xl p-3">
                @foreach($errors->all() as $e)<p class="text-red-600 text-sm">⚠️ {{ $e }}</p>@endforeach
            </div>
        @endif

        <div class="mt-4 bg-blue-50 rounded-xl p-3 text-sm text-blue-700">
            Jika kamu sudah mengisi data kesehatan di halaman awal, silakan cek lagi di sini sebelum melanjutkan.
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 4]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">🎉 Mulai NutriGo!</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const draft = localStorage.getItem('nutrigo.healthDraft');

        if (!draft) {
            return;
        }

        try {
            const data = JSON.parse(draft);
            const fields = ['age', 'gender', 'height_cm', 'weight_kg', 'activity_level'];

            fields.forEach((field) => {
                const input = document.querySelector(`[name="${field}"]`);

                if (input && data[field] && !input.value) {
                    input.value = data[field];
                }
            });
        } catch (error) {}
    });
</script>
@endsection
