<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Controller\Translate;

$routes = new RouteCollection();
$translateRoute = new Route('/translate', [
    '_controller' => [Translate::class, 'translate']
]);
$translateRoute->setMethods(['POST']);

$routes->add('translate', $translateRoute);

return $routes;