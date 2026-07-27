<?php

use CodeIgniter\Router\RouteCollection;

use App\Modules\Proyectos\Controllers\Proyectos_Controller;

/** @var RouteCollection $routes */

$routes->get('proyectos', [Proyectos_Controller::class, 'index']);

$routes->post(
    'proyectos',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::guardar'
);

$routes->get(
    'proyectos/(:num)',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::obtener/$1'
);