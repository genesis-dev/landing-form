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
    "table" => "landing_form",
];
$local = (include __DIR__."/local.php");
if (!is_array($local))
    $local = [];

return array_merge_recursive($config, $local);