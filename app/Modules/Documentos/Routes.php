<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('documentos', [
    'namespace' => 'App\Modules\Documentos\Controllers',
], static function ($routes) {

    $routes->get(
        '/',
        'Documentos_Controller::index'
    );
});