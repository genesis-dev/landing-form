#Installation

composer create-project "genesis-dev/landing-form":"v0.1.1-beta" landing-form

#Configuring
/config/local.php - overwrites /config/main.php (using array_merge_recursive)
```php
<?php
return [
    "db" => [ // Database settings
        'database_type' => '',
        'database_name' => '',
        'server' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
    ],
    "telegram" => [ // Telegram settings
        "api_key" => "",
    ],
    "defaults" => [
        "mailer" => [ // SMTP settings
            "host" => '',
            "username" => '',
            "password" => '',
            "from" => '',
            "fromName" => "",
            "subject" => "",
            "to" => ["mail@example.com", "mail2@example.com"],
        ],
        "telegram" => [
            "channel_name" => "", // Telegram channel name (without @)
            "include" => ["email", "phone"], // Names of fields to include in messages
        ],
    ],
    "sites" => [
        "siteID" => [ // Array, similar structure to defaults and overwrites them. Key is siteID.
        ],
        "test_null" => [ // Configurations for tests
            "mailer" => false,
        ],
        "test_mail" => [
        ],
        "test_telegram" => [
            "mailer" => false,
            "telegram" => [
                "channel_name" => "genesis_orders",
            ],
        ],
        "test_all" => [
            "telegram" => [
                "channel_name" => "genesis_orders",
            ],
        ],
    ],
];
```
/config/main.php
```php
<?php
$config = [
    "defaults" => [
        "validators" => [
            "email" => function($str, $fields, $validators) { // $str - field value, $fields - other fields, $validators - other validators
                return !empty($str) && filter_var($str, FILTER_VALIDATE_EMAIL);
            },
            "phone" => function($str, $fields, $validators) {
                return !empty($str) && preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $str);
            },
        ],
    ],
    "table" => "landing_form", // Table name in DB
];

//......
```


#Usage

```html
<!-- After jQuery script tag -->
<script src="<path_to_client_folder>?siteID=<siteID_from_config>&success_callback=<name_of_function>" defer></script>

<!-- Form markup -->
<form action="<path_to_service_root>" method="get" data-landing-form>
    <input type="text" name="landingForm[email]">
    <input type="text" name="landingForm[phone]">
    <input type="submit" value="Submit">
</form>
```
