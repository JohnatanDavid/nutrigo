@extends('onboarding.layout')
@section('content')
<div class="bg-white rounded-3xl shadow-xl p-8">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mt-3">Halo! Kenalan dulu yuk</h2>
        <p class="text-gray-500 mt-2">Kami ingin mengenalmu lebih dekat</p>
    </div>

    @php
        $needsNickname = blank($user->nickname);
        $needsBirthDate = blank($user->birth_date);
        $needsGender = blank($user->gender);
        $needsCity = blank($user->city);
    @endphp

    <form method="POST" action="{{ route('onboarding.save.1') }}">
        @csrf
        <div class="space-y-5">
            @if($needsNickname)
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">
                        Kamu mau dipanggil apa?
                    </label>
                    <input type="text" name="nickname" value="{{ old('nickname', $user->nickname ?: $user->name) }}"
                           class="input-field" placeholder="Panggil aku..." required>
                </div>
            @endif

            @if($needsBirthDate)
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Tanggal Lahir</label>
                    <div class="grid grid-cols-3 gap-3">
                        <select name="birth_day" class="input-field" required>
                            <option value="">Hari</option>
                            @for($d=1; $d<=31; $d++)
                                <option value="{{ $d }}" {{ old('birth_day', optional($user->birth_date)->day) == $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endfor
                        </select>
                        <select name="birth_month" class="input-field" required>
                            <option value="">Bulan</option>
                            @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $m)
                                <option value="{{ $i+1 }}" {{ old('birth_month', optional($user->birth_date)->month) == $i+1 ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                        <select name="birth_year" class="input-field" required>
                            <option value="">Tahun</option>
                            @for($y=date('Y')-80; $y<=date('Y')-5; $y++)
                                <option value="{{ $y }}" {{ old('birth_year', optional($user->birth_date)->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            @endif

            @if($needsGender)
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Jenis Kelamin</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer
                            {{ old('gender', $user->gender) == 'male' ? 'border-ng-orange bg-orange-50' : 'border-gray-200 hover:border-ng-orange' }}">
                            <input type="radio" name="gender" value="male" class="hidden" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>
                            <span class="font-semibold text-gray-700">Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer
                            {{ old('gender', $user->gender) == 'female' ? 'border-ng-orange bg-orange-50' : 'border-gray-200 hover:border-ng-orange' }}">
                            <input type="radio" name="gender" value="female" class="hidden" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>
                            <span class="font-semibold text-gray-700">Perempuan</span>
                        </label>
                    </div>
                </div>
            @endif

            @if($needsCity)
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Kota</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" class="input-field" placeholder="Contoh: Bandung" required>
                </div>
            @endif
        </div>

        @if($errors->any())
            <div class="mt-4 bg-red-50 rounded-xl p-3">
                @foreach($errors->all() as $e)<p class="text-red-600 text-sm">⚠️ {{ $e }}</p>@endforeach
            </div>
        @endif

        <div class="mt-8 flex justify-between">
            <a href="{{ route('onboarding.step', ['step' => 1]) }}" class="btn-outline">← Kembali</a>
            <button type="submit" class="btn-primary">Lanjut →</button>
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
