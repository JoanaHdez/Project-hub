<?php

use CodeIgniter\Router\RouteCollection;
use App\Modules\Proyectos\Controllers\Proyectos_Controller;

/** @var RouteCollection $routes */


/*==================================================
=                   PROYECTOS                      =
==================================================*/

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


/*==================================================
=            ESPECIFICACIONES TÉCNICAS             =
==================================================*/

$routes->post(
    'proyectos/especificaciones',
    [
        Proyectos_Controller::class,
        'guardarEspecificacion',
    ],
    [
        'filter' => 'admin',
    ]
);


$routes->get(
    'proyectos/especificaciones/(:num)',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::obtenerEspecificacion/$1',
    [
        'filter' => 'admin',
    ]
);


/*==================================================
=                OBTENER PROYECTO                  =
==================================================*/

$routes->get(
    'proyectos/(:num)',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::obtener/$1',
    [
        'filter' => 'admin',
    ]
);


/*==================================================
=              ACTUALIZAR PROYECTO                 =
==================================================*/

$routes->put(
    'proyectos/(:num)',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::actualizar/$1',
    [
        'filter' => 'admin',
    ]
);


/*==================================================
=              DESACTIVAR PROYECTO                 =
==================================================*/

$routes->patch(
    'proyectos/(:num)/desactivar',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::desactivar/$1',
    [
        'filter' => 'admin',
    ]
);


/*==================================================
=                ELIMINAR PROYECTO                 =
==================================================*/

$routes->delete(
    'proyectos/(:num)',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::eliminar/$1',
    [
        'filter' => 'admin',
    ]
);


/*==================================================
=                 ACTIVAR PROYECTO                 =
==================================================*/

$routes->patch(
    'proyectos/(:num)/activar',
    '\App\Modules\Proyectos\Controllers\Proyectos_Controller::activar/$1',
    [
        'filter' => 'admin',
    ]
);