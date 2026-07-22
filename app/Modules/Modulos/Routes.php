<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('modulos', [
    'namespace' => 'App\Modules\Modulos\Controllers',
], static function ($routes) {
    $routes->get('/', 'Modulos_Controller::index');
});