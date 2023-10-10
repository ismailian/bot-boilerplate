<?php

error_reporting(0);

/*
|--------------------
| Composer autoload |
|--------------------
*/

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

/*
|-----------------------------
| Initialize a new API App   |
|-----------------------------
*/

use PhpApi\Api;

/** new Instance of Api App */
$ApiApp = new Api($options = [
    'prefix' => '/api/v1',
    'Cors' => [
        'origin'  => '*',
        'methods' => ['*'],
        'headers' => ['*']
    ],
]);


/*
|-----------------------------
| Handle Routing             |
|-----------------------------
*/

$ApiApp->get('/', 'Home.Index');
$ApiApp->post('/events', 'Telegram.Index');