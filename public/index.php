<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

$container = [
    'settings' => [
        'addContentLengthHeader' => false,
        'displayErrorDetails' => true,
        'google' => [
            'access_token' => 'token'
        ]
    ],
];

$container['logger'] = function(Container $container) {
    $logger = new \Monolog\Logger('translater');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);

    return $logger;
};

$container['twig'] = function (Container $container) {
    $view = new \Slim\Views\Twig('templates', [
//        'cache' => '../cache/templates'
    ]);

    /** @var Request $request */
    $request = $container->get('request');

    $basePath = rtrim(str_ireplace('index.php', '', $request->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
};

$app = new \Slim\App($container);

/** @var \Monolog\Logger $logger */
$logger = $app->getContainer()->get('logger');
$logger->info('Starting app...');

$app->get('/dialogContents', function (Request $request, Response $response) {
    $this->logger->info('Response...');

    /** @var \Slim\Views\Twig $twig */
    $twig = $this->twig;
    $result = [
        'status' => true,
        'dialogs' => [
            'icon' => [
                'tag' => 'div',
                'attributes' => [
                    'id' => 'custom_translate',
                    'style' => [
                        'width' => '19px',
                        'height' => '19px',
                        'border' => '1px solid black',
                        'position' => 'absolute !important',
                        'border-radius' => '5px'
                    ]
                ],
                'content' => $twig->fetch('icon.html')
            ],
            'popup' => [
                'tag' => 'div',
                'attributes' => [
                    'id' => 'translate_ext_rayman',
                    'style' => [
                        'width' => '100px',
                        'height' => '100px',
                        'border' => '1px solid black',
                        'position' => 'absolute !important',
                        'background-color' => 'white',
                        'z-index' => 9999
                    ]
                ],
            ]
        ]
    ];

    /** @var \Slim\Views\Twig $twig */
    return $response->withJson($result);
});

$app->post('/translate', function (Request $request, Response $response) {
    $this->logger->info('Response...');
    $result = [
        'status' => true,
        'translate_result' => []
    ];

    /** @var \Slim\Views\Twig $twig */
    $twig = $this->twig;
    $params = $request->getParsedBody();
    if (empty($params['text'])) {
        $result['status'] = false;
        $result['error'] = 'There is not text to translate';
    } else {
        $result['data'] = [
            'text' => 'Перевод текста " ' . $params['text'] . ' "',
            'content' => $twig->fetch('popup.html', $params)
        ];
    }

    /** @var \Slim\Views\Twig $twig */
    return $response->withJson($result);
});

$app->run();