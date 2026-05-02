<?php
return [
    // Pisahkan beberapa email admin dengan koma
    'admin_emails' => env('NUTRIGO_ADMIN_EMAILS', 'admin@nutrigo.id'),

    'activity_multipliers' => [
        'sedentary'   => 1.2,
        'light'       => 1.375,
        'moderate'    => 1.55,
        'active'      => 1.725,
        'very_active' => 1.9,
    ],

    'meal_distribution' => [
        'breakfast' => 0.30,
        'lunch'     => 0.40,
        'dinner'    => 0.30,
    ],

    'allergens' => [
        'gluten','seafood','shellfish','nuts','peanuts',
        'dairy','eggs','soy','wheat','sesame',
        'kacang','susu','telur','udang','kepiting','cumi',
    ],

    'provinces' => [
        'Aceh','Sumatera Utara','Sumatera Barat','Riau','Kepulauan Riau',
        'Jambi','Sumatera Selatan','Bangka Belitung','Bengkulu','Lampung',
        'DKI Jakarta','Jawa Barat','Banten','Jawa Tengah','DI Yogyakarta',
        'Jawa Timur','Bali','Nusa Tenggara Barat','Nusa Tenggara Timur',
        'Kalimantan Barat','Kalimantan Tengah','Kalimantan Selatan',
        'Kalimantan Timur','Kalimantan Utara','Sulawesi Utara',
        'Sulawesi Tengah','Sulawesi Selatan','Sulawesi Tenggara',
        'Gorontalo','Sulawesi Barat','Maluku','Maluku Utara',
        'Papua','Papua Barat','Papua Selatan','Papua Tengah',
        'Papua Pegunungan',
    ],
];