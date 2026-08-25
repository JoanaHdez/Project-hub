<?php

namespace App\Modules\MisSistemas\Controllers;

use App\Controllers\BaseController;

use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Modulos\Services\Modulo_StorageService;


class MisSistemas_Controller extends BaseController
{
    private Sistema_StorageService $sistemaStorage;

    private Proyecto_StorageService $proyectoStorage;

    private Modulo_StorageService $moduloStorage;


    public function __construct()
    {
        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->moduloStorage =
            new Modulo_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $sistemas =
            $this->sistemaStorage
                ->obtenerTodos();

        $proyectos =
            $this->proyectoStorage
                ->obtenerTodos();

        $modulos =
            $this->moduloStorage
                ->obtenerTodos();


        /*==================================================
        =              NOMBRES DE PROYECTOS                =
        ==================================================*/

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {

            $idProyecto =
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[
                $idProyecto
            ] =
                $proyecto['nombre']
                ?? 'Sin proyecto';
        }


        /*==================================================
        =              SISTEMAS ACTIVOS                    =
        ==================================================*/

        $sistemasActivos = [];

        foreach ($sistemas as $sistema) {

            $idSistema =
                (int) (
                    $sistema['id_sistema']
                    ?? 0
                );

            if ($idSistema <= 0) {
                continue;
            }


            $activo =
                (bool) (
                    $sistema['activo']
                    ?? false
                );

            if (!$activo) {
                continue;
            }


            $idProyecto =
                (int) (
                    $sistema['id_proyecto']
                    ?? 0
                );


            $sistemasActivos[
                $idSistema
            ] = [
                ...$sistema,

                'proyecto_nombre' =>
                    $nombresProyectos[
                        $idProyecto
                    ]
                    ?? 'Sin proyecto',
            ];
        }


        /*==================================================
        =              ACCESOS PÚBLICOS                   =
        ==================================================*/

        $accesos = [];


        /*==================================================
        =              AGREGAR SISTEMAS                   =
        ==================================================*/

        foreach (
            $sistemasActivos
            as $sistema
        ) {

            $idSistema =
                (int) (
                    $sistema['id_sistema']
                    ?? 0
                );


            $accesos[] = [

                'tipo_acceso' =>
                    'sistema',

                'id_sistema' =>
                    $idSistema,

                'id_modulo' =>
                    null,

                'nombre' =>
                    trim(
                        (string) (
                            $sistema['nombre']
                            ?? 'Sistema'
                        )
                    ),

                'tipo' =>
                    trim(
                        (string) (
                            $sistema['tipo']
                            ?? 'Sistema'
                        )
                    ),

                'descripcion' =>
                    trim(
                        (string) (
                            $sistema['descripcion']
                            ?? ''
                        )
                    ),

                'url' =>
                    trim(
                        (string) (
                            $sistema['url']
                            ?? ''
                        )
                    ),

                /*
                 * Los sistemas actualmente no manejan
                 * imagen pública como los módulos.
                 */
                'imagen' =>
                    '',

                'activo' =>
                    true,

                'sistema_nombre' =>
                    $sistema['nombre']
                    ?? 'Sistema',

                'sistema_tipo' =>
                    $sistema['tipo']
                    ?? 'Sistema',

                'proyecto_nombre' =>
                    $sistema['proyecto_nombre']
                    ?? 'Sin proyecto',
            ];
        }


        /*==================================================
        =              AGREGAR MÓDULOS                    =
        ==================================================*/

        foreach ($modulos as $modulo) {

            $idSistema =
                (int) (
                    $modulo['id_sistema']
                    ?? 0
                );


            /*
             * El módulo solamente se muestra
             * si su sistema padre está activo.
             */
            if (
                $idSistema <= 0
                ||
                !isset(
                    $sistemasActivos[
                        $idSistema
                    ]
                )
            ) {
                continue;
            }


            $moduloActivo =
                (bool) (
                    $modulo['activo']
                    ?? false
                );

            if (!$moduloActivo) {
                continue;
            }


            $sistema =
                $sistemasActivos[
                    $idSistema
                ];


            $accesos[] = [

                'tipo_acceso' =>
                    'modulo',

                'id_modulo' =>
                    (int) (
                        $modulo['id_modulo']
                        ?? 0
                    ),

                'id_sistema' =>
                    $idSistema,

                'nombre' =>
                    trim(
                        (string) (
                            $modulo['nombre']
                            ?? 'Módulo'
                        )
                    ),

                'tipo' =>
                    trim(
                        (string) (
                            $modulo['tipo']
                            ?? 'Módulo'
                        )
                    ),

                'descripcion' =>
                    trim(
                        (string) (
                            $modulo['descripcion']
                            ?? ''
                        )
                    ),

                'url' =>
                    trim(
                        (string) (
                            $modulo['url']
                            ?? ''
                        )
                    ),

                'imagen' =>
                    trim(
                        (string) (
                            $modulo['imagen']
                            ?? ''
                        )
                    ),

                'activo' =>
                    true,

                'sistema_nombre' =>
                    $sistema['nombre']
                    ?? 'Sistema',

                'sistema_tipo' =>
                    $sistema['tipo']
                    ?? 'Sistema',

                'proyecto_nombre' =>
                    $sistema['proyecto_nombre']
                    ?? 'Sin proyecto',
            ];
        }


        /*==================================================
        =                    VISTA                          =
        ==================================================*/

        return view(
            'App\Modules\MisSistemas\Views\index',
            [
                'title' =>
                    'Mis sistemas | Project Hub',

                /*
                 * Conservamos "sistemas" para no
                 * romper la vista actual.
                 *
                 * Ahora contiene tanto sistemas
                 * como módulos.
                 */
                'sistemas' =>
                    $accesos,
            ]
        );
    }
}