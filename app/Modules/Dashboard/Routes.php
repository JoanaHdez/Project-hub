<?php
use CodeIgniter\Router\RouteCollection;
use App\Modules\Dashboard\Controllers\DashboardController;

/** @var RouteCollection $routes */

$routes->get('/', [DashboardController::class, 'index']);
$routes->get('dashboard', [DashboardController::class, 'index']);