<?php

use CodeIgniter\Router\RouteCollection;
use App\Modules\MisSistemas\Controllers\MisSistemas_Controller;

/** @var RouteCollection $routes */

$routes->get(
    'mis-sistemas',
    [
        MisSistemas_Controller::class,
        'index',
    ],
    [
        'filter' => 'auth',
    ]
);