<?php

use CodeIgniter\Router\RouteCollection;
use App\Modules\Proyectos\Controllers\Proyectos_Controller;

/** @var RouteCollection $routes */


$routes->get(
    'proyectos',
    [
        Proyectos_Controller::class,
        'index',
    ],
    [
        'filter' => 'admin',
    ]
);


$routes->post(
    'proyectos',
    [
        Proyectos_Controller::class,
        'guardar',
    ],
    [
        'filter' => 'admin',
    ]
);


$routes->get(
    'proyectos/(:num)',
    [
        Proyectos_Controller::class,
        'obtener',
    ],
    [
        'filter' => 'admin',
    ]
);


$routes->put(
    'proyectos/(:num)',
    [
        Proyectos_Controller::class,
        'actualizar',
    ],
    [
        'filter' => 'admin',
    ]
);


$routes->patch(
    'proyectos/(:num)/desactivar',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::desactivar/$1',
    [
        'filter' => 'admin',
    ]
);


$routes->delete(
    'proyectos/(:num)',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::eliminar/$1',
    [
        'filter' => 'admin',
    ]
);


$routes->patch(
    'proyectos/(:num)/activar',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::activar/$1',
    [
        'filter' => 'admin',
    ]
);