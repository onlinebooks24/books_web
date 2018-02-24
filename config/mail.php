<?php

//    return [
//        'driver' => env('MAIL_DRIVER', 'smtp'),
//        'host' => env('MAIL_HOST', 'smtp.gmail.com'),
//        'port' => env('MAIL_PORT', 587),
//        'from' => ['address' => 'somratcste@gmail.com', 'name' => 'G. M. Nazmul Hossain Somrat'],
//        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
//        'username' => env('MAIL_USERNAME'),
//        'password' => env('MAIL_PASSWORD'),
//        'sendmail' => '/usr/sbin/sendmail -bs',
//        'pretend' => false,
//    ];


if (env('APP_ENV') != 'production') {
    return array(
        "driver" => env('SES_MAIL_DRIVER', 'smtp'),
        "host" => env('SES_MAIL_HOST', 'email-smtp.us-west-2.amazonaws.com'),
        "port" => env('SES_MAIL_PORT', '587'),
        'encryption' => 'tls',
        "from" => array(
            "address" => "info@onlinebooksreview.com",
            "name" => "OnlineBooksReview"
        ),
        "username" => env('SES_MAIL_USERNAME'),
        "password" => env('SES_MAIL_PASSWORD'),
        'sendmail' => '/usr/sbin/sendmail -bs',
        "pretend" => false
    );

} else {
    return array(
        "driver" => env('MAILTRAP_MAIL_DRIVER', 'smtp'),
        "host" => env('MAILTRAP_MAIL_HOST', 'smtp.mailtrap.io'),
        "port" => env('MAILTRAP_MAIL_PORT', '2525'),
        "from" => array(
            "address" => "info@onlinebooksreview.com",
            "name" => "OnlineBooksReview"
        ),
        "username" => env('MAILTRAP_MAIL_USERNAME'),
        "password" => env('MAILTRAP_MAIL_PASSWORD'),
        "sendmail" => "/usr/sbin/sendmail -bs",
        "pretend" => false
    );
}

