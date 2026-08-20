<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('documentos', [
    'namespace' => 'App\Modules\Documentos\Controllers',
], static function ($routes) {

    $routes->get('/', 'Documentos_Controller::index');

    $routes->get('nuevo', 'Documentos_Controller::nuevo');

    $routes->post('/', 'Documentos_Controller::crear');

    $routes->get('sistema/(:num)', 'Documentos_Controller::sistema/$1');

    $routes->delete('(:num)', 'Documentos_Controller::eliminar/$1');

    $routes->get('sistema/(:num)/nuevo', 'Documentos_Controller::nuevoSistema/$1');
});
