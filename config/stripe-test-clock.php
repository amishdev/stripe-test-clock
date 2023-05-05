<?php

return [
    'enabled' => in_array(env('APP_ENV'), ['local', 'staging']),

    'stripe_secret' => env('STRIPE_SECRET'),
];