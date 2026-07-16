<?php

use CodeIgniter\Router\RouteCollection;

use App\Modules\Sistemas\Controllers\Sistemas_Controller;

/** @var RouteCollection $routes */

$routes->get('sistemas', [Sistemas_Controller::class, 'index']);