<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('apis', ['namespace' => 'App\Modules\APIs\Controllers'], static function ($routes) {

$routes->get('/', 'APIs_Controller::index');

$routes->post('/', 'APIs_Controller::guardar');

$routes->put('(:num)', 'APIs_Controller::actualizar/$1');

$routes->patch('(:num)/desactivar', 'APIs_Controller::desactivar/$1');

$routes->patch('(:num)/activar', 'APIs_Controller::activar/$1');

$routes->delete('(:num)', 'APIs_Controller::eliminar/$1');

$routes->patch('(:num)/arquitectura', 'APIs_Controller::actualizarArquitectura/$1');

$routes->delete('(:num)/arquitectura', 'APIs_Controller::eliminarArquitectura/$1');

$routes->patch('(:num)/dependencias', 'APIs_Controller::actualizarDependencias/$1');

$routes->delete('(:num)/dependencias', 'APIs_Controller::eliminarDependencias/$1');

$routes->patch('(:num)/observaciones', 'APIs_Controller::actualizarObservaciones/$1');
});