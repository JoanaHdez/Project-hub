<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('apis', ['namespace' => 'App\Modules\APIs\Controllers'], static function ($routes) {

$routes->get('/', 'APIs_Controller::index');

});