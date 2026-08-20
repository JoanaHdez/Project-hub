<?php
use CodeIgniter\Router\RouteCollection;
use App\Modules\Dashboard\Controllers\DashboardController;

/** @var RouteCollection $routes */

$routes->group(
    '',
    [
        'namespace' =>
            'App\Modules\Dashboard\Controllers',
    ],
    static function ($routes) {

        $routes->get(
            '/',
            'DashboardController::index'
        );

        $routes->get(
            'dashboard',
            'DashboardController::index'
        );

        $routes->get(
            'actividad',
            'DashboardController::actividad'
        );
    }
);