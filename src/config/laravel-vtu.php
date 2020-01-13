<?php

return [
    'default' => \Djunehor\Vtu\Concrete\VoiceAndText::class,
    'voice_and_text' => [
        'api_token' => env('VOICE_AND_TEXT_TOKEN'),
        'callback' => env('VOICE_AND_TEXT_CALLBACK'),
    ],
    'cowriesys' => [
        'client_id' => env('COWRIESYS_CLIENT_ID'),
        'client_key' => env('COWRIESYS_CLIENT_KEY'),
    ],
    'vtpass' => [
        'username' => env('VTPASS_USERNAME'),
        'password' => env('VTPASS_PASSWORD'),
    ],
    'fpe_vtu' => [
        'username' => env('FPEVTU_USERNAME'),
        'password' => env('FPEVTU_PASSWORD'),
    ],
    'unik_mobile' => [
        'username' => env('UNIK_MOBILE_USERNAME'),
        'password' => env('UNIK_MOBILE_PASSWORD'),
    ],
];
