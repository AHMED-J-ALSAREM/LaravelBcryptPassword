<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bcrypt Cost
    |--------------------------------------------------------------------------
    |
    | Here you may specify the bcrypt hashing cost that should be used when
    | hashing new passwords. Default is 12.
    |
    */
    'options' => [
        'cost' => env('BCRYPT_COST', 12),
        'iteration_count_log2' => env('WP_ITERATION_COUNT_LOG2', 8),
        'portable_passwords' => env('WP_PORTABLE_PASSWORDS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Validation
    |--------------------------------------------------------------------------
    |
    | Here you may specify the maximum length that a password may be before
    | it is rejected for security purposes. Default from WordPress is 4096.
    |
    */
    'validation' => [
        'max_length' => env('PASSWORD_MAX_LENGTH', 4096),
    ],
];
