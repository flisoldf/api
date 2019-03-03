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
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
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

        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode' => true,

            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir' => APP_ROOT . '/var/doctrine',

            // you should add any other path containing annotated entity classes
            'metadata_dirs' => [APP_ROOT . '/src/Domain'],

            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'port' => 3306,
                'dbname' => 'mydb',
                'user' => 'user',
                'password' => 'secret',
                'charset' => 'utf-8'
            ]
        ]
    ],
];
