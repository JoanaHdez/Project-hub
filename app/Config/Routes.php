<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

require APPPATH . 'Modules/Dashboard/Routes.php';
require APPPATH . 'Modules/Proyectos/Routes.php';
require APPPATH . 'Modules/Sistemas/Routes.php';
require APPPATH . 'Modules/APIs/Routes.php';

$archivoRutasModulos = APPPATH . 'Modules/Modulos/Routes.php';

if (is_file($archivoRutasModulos)) {
    require $archivoRutasModulos;
}