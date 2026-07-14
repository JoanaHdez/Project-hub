<?php

use CodeIgniter\Router\RouteCollection;

use App\Modules\Proyectos\Controllers\Proyectos_Controller;

/** @var RouteCollection $routes */

$routes->get('proyectos', [Proyectos_Controller::class, 'index']);