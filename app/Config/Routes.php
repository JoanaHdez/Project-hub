<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */


/*==================================================
=                     AUTH                         =
==================================================*/

require APPPATH . 'Modules/Auth/Routes.php';


/*==================================================
=                    MÓDULOS                       =
==================================================*/

require APPPATH . 'Modules/Dashboard/Routes.php';
require APPPATH . 'Modules/Proyectos/Routes.php';
require APPPATH . 'Modules/Sistemas/Routes.php';
require APPPATH . 'Modules/APIs/Routes.php';
require APPPATH . 'Modules/Documentos/Routes.php';
require APPPATH . 'Modules/MisSistemas/Routes.php';


/*==================================================
=                    MÓDULOS                       =
==================================================*/

$archivoRutasModulos =
    APPPATH . 'Modules/Modulos/Routes.php';

if (is_file($archivoRutasModulos)) {

    require $archivoRutasModulos;
}