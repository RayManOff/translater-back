<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Http\Request;
use Slim\Http\Response;

$container = [
    'settings' => [
        'addContentLengthHeader' => false,
        'displayErrorDetails' => true,
        'google' => [
            'access_token' => 'token'
        ]
    ]
];

$container['logger'] = function(\Slim\Container $container) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);

    return $logger;
};

$app = new \Slim\App($container);

/** @var \Monolog\Logger $logger */
$logger = $app->getContainer()->get('logger');
$logger->info('Starting app...');

// Define app routes
$app->post('/translate', function (Request $request, Response $response) {
    $this->logger->info('Response...');
    $feed = $request->getParsedBody();

    return $response->withJson($feed);
});

// Run app
$app->run();