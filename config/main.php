<?php
$config = [
    "db" => [
        'database_type' => 'mysql',
        'database_name' => 'name',
        'server' => 'localhost',
        'username' => 'your_username',
        'password' => 'your_password',
        'charset' => 'utf8',
    ],
    "table" => "landing_form",
    "defaults" => [
        "validators" => [
            "email" => function($str, $fields, $validators) {
                return !empty($str) && filter_var($str, FILTER_VALIDATE_EMAIL);
            },
            "phone" => function($str, $fields, $validators) {
                return !empty($str) && preg_match('^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$', $str);
            },
        ],
        "exclude" => [],
    ],
];
$local = (include __DIR__."/local.php");
if (!is_array($local))
    $local = [];

return array_merge($config, $local);