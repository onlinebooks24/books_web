<?php

if (env('APP_ENV') == 'production') {
    return array(
        "driver" => env('SES_MAIL_DRIVER', 'smtp'),
        "host" => env('SES_MAIL_HOST', 'email-smtp.us-west-2.amazonaws.com'),
        "port" => env('SES_MAIL_PORT', '2525'),
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