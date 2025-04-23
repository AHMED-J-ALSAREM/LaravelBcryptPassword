<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bcrypt Cost
    |--------------------------------------------------------------------------
    |
    | Here you may specify the bcrypt hashing cost that should be used when
    | hashing passwords. The default cost is 12, which is a good balance
    | between speed and security for most applications.
    |
    */
    'cost' => env('BCRYPT_COST', 12),
];