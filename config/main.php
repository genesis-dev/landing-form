<?php
$config = [
    "defaults" => [
        "validators" => [
            "email" => function($str, $fields, $validators) {
                if (empty($str))
                    return true;
                return filter_var($str, FILTER_VALIDATE_EMAIL);
            },
            "phone" => function($str, $fields, $validators) {
                if (empty($str))
                    return true;
                return preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $str);
            },
            "special_validators" => [
                "required" => function($fields, $validators) {
                    return empty($fields['email']['value']) || empty($fields['phone']['value']);
                },
            ],
        ],
        "exclude" => [],
    ],
    "table" => "landing_form",
];
$local = (include __DIR__."/local.php");
if (!is_array($local))
    $local = [];

return array_merge_recursive($config, $local);