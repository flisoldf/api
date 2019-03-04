<?php
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
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Configuração do Swagger - gerador de documentação de api
        'swagger' => [
            // Pasta raiz que será escaneada em busca de Annotations. Um exemplo é chamado se a url não for configurada.
            'baseDir' => __DIR__ . '/../vendor/junioalmeida/slim-framework-swagger-json-and-viewer/Examples/petstore.swagger.io', // Link de exemplo
            //  'baseDir' => __DIR__ . '/../src', // Geralmente as classes do projeto fica na pasta \src\
            //  'ignoreDir' => [],
            //  'routes' => [
            //    'json' => '/docs/json',
            //    'view' => '/docs/view',
            //    'resources' => '/docs/resources/{resource}',
            //  ],
            //  'projects' => [
            //    ['name'=>'This project', 'url'=>'/docs/json'],
            //    ['name'=>'Exemplo', 'url'=>'http://petstore.swagger.io/v2/swagger.json'],
            //  ],
        ],

        'uploads' => [
            'talk' => __DIR__ . '/../../api-data/uploads/talk',
            'person' => __DIR__ . '/../../api-data/uploads/person',
            'community' => __DIR__ . '/../../api-data/uploads/community',
        ],

        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'netinhoi_flisoldf_api',
            'username' => 'root',
            'password' => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
    ],
];
