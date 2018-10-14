<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Controller\Translate;

$translateRoute = new Route('/translate', [
    '_controller' => [Translate::class, 'translate']
]);
$getIconRoute = new Route('/getIcon', [
    '_controller' => [Translate::class, 'getIcon']
]);

$translateRoute->setMethods(['GET']);
$translateRoute->setMethods(['GET']);

$routes = new RouteCollection();
$routes->add('translate', $translateRoute);
$routes->add('getIcon', $getIconRoute);

return $routes;