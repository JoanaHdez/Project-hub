<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group(
    '',
    [
        'namespace' =>
            'App\Modules\Auth\Controllers',
    ],
    static function ($routes) {

        $routes->get(
            'login',
            'Auth_Controller::login'
        );

        $routes->post(
            'login',
            'Auth_Controller::autenticar'
        );

        $routes->get(
            'logout',
            'Auth_Controller::logout'
        );
    }
);