<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of variables that are made specific to this application
    | that are better placed here rather than in .env file.
    | Use config('your_key') to get the values.
    |
    */

    'dollar_rate' => env('DOLLAR_RATE','80'),
    'twilio_from_number' => env('TWILIO_FROM_NUMBER','+8801670633325'),
    'admin_number' => env('ADMIN_NUMBER','+16138006902'),

];