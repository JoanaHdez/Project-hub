<?php

use CodeIgniter\Router\RouteCollection;
use App\Modules\Proyectos\Controllers\Proyectos_Controller;

/** @var RouteCollection $routes */

$routes->get('proyectos', [
    Proyectos_Controller::class,
    'index',
]);

$routes->post('proyectos', [
    Proyectos_Controller::class,
    'guardar',
]);

$routes->get('proyectos/(:num)', [
    Proyectos_Controller::class,
    'obtener',
]);

$routes->put('proyectos/(:num)', [
    Proyectos_Controller::class,
    'actualizar',
]);

$routes->patch(
    'proyectos/(:num)/desactivar',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::desactivar/$1'
);

$routes->delete(
    'proyectos/(:num)',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::eliminar/$1'
);

$routes->patch(
    'proyectos/(:num)/activar',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::activar/$1'
);