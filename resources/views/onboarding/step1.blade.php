@extends('onboarding.layout')
@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 mt-3">Halo! Kenalan dulu yuk</h2>
        <p class="text-base text-gray-600 mt-2">Kami ingin mengenalmu lebih dekat</p>
    </div>

    <form method="POST" action="{{ route('onboarding.save.1') }}">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Nama Panggilan</label>
                <input type="text" name="nickname" value="{{ old('nickname', $user->nickname ?: $user->name) }}" class="input-field" placeholder="Contoh: Vita" required>
                <p class="text-xs text-gray-500 mt-1">Nama ini akan ditampilkan di dashboard kamu.</p>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Jenis Kelamin</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="card-selector {{ old('gender', $user->gender) == 'male' ? 'card-selector-active' : 'card-selector-inactive' }}">
                        <input type="radio" name="gender" value="male" class="form-radio hidden" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>
                        <div class="font-semibold">Laki-laki</div>
                    </label>
                    <label class="card-selector {{ old('gender', $user->gender) == 'female' ? 'card-selector-active' : 'card-selector-inactive' }}">
                        <input type="radio" name="gender" value="female" class="form-radio hidden" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>
                        <div class="font-semibold">Perempuan</div>
                    </label>
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Kota</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="input-field" placeholder="Contoh: Malang" required>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}" class="input-field" required>
            </div>
        </div>

        @include('onboarding._errors')

        <div class="mt-8 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 1]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">Selanjutnya →</button>
        </div>
    </form>
</div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Make gender picker interactive by toggling classes on labels
        const inputs = document.querySelectorAll('input[name="gender"]');
        const labels = Array.from(inputs).map(i => i.closest('label'));

        function refresh() {
            labels.forEach(label => {
                const input = label.querySelector('input[name="gender"]');
                if (input && input.checked) {
                    label.classList.add('border-ng-orange','bg-orange-50');
                    label.classList.remove('border-gray-200');
                } else {
                    label.classList.remove('border-ng-orange','bg-orange-50');
                    label.classList.add('border-gray-200');
                }
            });
        }

        inputs.forEach(i => i.addEventListener('change', refresh));
        // initial state
        refresh();
    });
    </script>
@endsection
