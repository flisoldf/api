<?php

use Monolog\Logger;

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => false,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../../api-data/logs/app.log',
            'level' => Logger::DEBUG,
        ],

        'uploads' => [
            'talk' => __DIR__ . '/../../api-data/uploads/talk',
            'person' => __DIR__ . '/../../api-data/uploads/person',
            'community' => __DIR__ . '/../../api-data/uploads/community',
            'participant' => __DIR__ . '/../../api-data/uploads/participant',
        ],

        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'netinhoi_flisoldf_api',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],

        'telegram' => [
            'FlisolDFAdministracaoBot' => [
                'token' => '<bot>:<token>',
            ],
        ],

        'doity' => [
            'authentication' => [
                'username' => '<username>',
                'password' => '<password>',
            ],
            'url' => [
                'login' => 'https://doity.com.br/admin/users/login',
                'event' => 'https://doity.com.br/admin/eventos/painel/<event id>',
            ],
        ],

        // Reference: https://www.google.com/recaptcha/admin/site/345316850/settings
        // Reference: https://stackoverflow.com/questions/51507695/google-recaptcha-v3-example-demo
        'catpcha' => [
            'siteKey' => '<site-key>',
            'secretKey' => '<secret-key>',
        ],

        'edition' => 15,
    ],

];
