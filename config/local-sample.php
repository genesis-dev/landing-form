<?php
return [
    "db" => [ //DB config
        'database_type' => 'mysql',
        'database_name' => '',
        'server' => 'localhost',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
    ],
    "telegram" => [
        "api_key" => "", // Your API key
    ],
    "defaults" => [
        "mailer" => [ // SMTP settings
            "host" => 'smtp.yandex.ru',
            "username" => '',
            "password" => '',
            "from" => '',
            "fromName" => "",
            "subject" => "",
            "to" => [],
        ],
    ],
    "sites" => [
        "academy-kz-landing" => [
            "mailer" => [
                "subject" => "23academy.kz. Новая заявка",
            ],
            "telegram" => [
                "channel_name" => "orders23academy",
            ],
            "include" => ["email", "phone"],
        ],
        /* Test cases: */
        "test_null" => [
            "mailer" => false,
        ],
        "test_mail" => [

        ],
        "test_telegram" => [
            "mailer" => false,
            "telegram" => [
                "channel_name" => "",
            ],
        ],
        "test_all" => [
            "telegram" => [
                "channel_name" => "",
            ],
        ],
    ],
];

