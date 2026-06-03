@php
    // Map common validation fields to friendly Indonesian messages.
    $mapped = [];
    foreach ($errors->keys() as $field) {
        if (in_array($field, ['nickname'])) {
            $mapped[] = 'Nama panggilan wajib diisi.';
        } elseif (in_array($field, ['gender'])) {
            $mapped[] = 'Jenis kelamin wajib dipilih.';
        } elseif (in_array($field, ['city'])) {
            $mapped[] = 'Kota wajib diisi.';
        } elseif (in_array($field, ['birth_date','birth_day','birth_month','birth_year'])) {
            $mapped[] = 'Tanggal lahir wajib diisi.';
        } elseif (in_array($field, ['has_allergy'])) {
            $mapped[] = 'Pilih apakah Anda memiliki alergi makanan.';
        } elseif (in_array($field, ['food_item','quantity','unit','duration_type','has_medical_need'])) {
            $mapped[] = 'Lengkapi detail kebutuhan khusus jika memilih "Ya".';
        } else {
            foreach ($errors->get($field) as $msg) {
                $mapped[] = $msg; // fallback: show original
            }
        }
    }
@endphp

@if(!empty($mapped))
    <div class="mt-4 bg-red-50 rounded-xl p-3">
        @foreach($mapped as $m)
            <p class="text-red-600 text-sm">⚠️ {{ $m }}</p>
        @endforeach
    </div>
@endif
