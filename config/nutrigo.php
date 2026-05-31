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
        'Jawa Timur',
        'Jawa Tengah',
        'Jawa Barat',
        'DKI Jakarta',
        'DI Yogyakarta',
        'Banten',
    ],
];