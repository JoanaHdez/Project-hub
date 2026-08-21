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

            $nombresProyectos[$idProyecto] =
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


            /*
             * Solo permitimos sistemas
             * expresamente activos.
             */
            $activo =
                isset($sistema['activo'])
                && $sistema['activo'] === true;

            if (!$activo) {
                continue;
            }


            $idProyecto =
                (int) (
                    $sistema['id_proyecto']
                    ?? 0
                );


            $sistemasActivos[$idSistema] = [
                ...$sistema,

                'proyecto_nombre' =>
                    $nombresProyectos[$idProyecto]
                    ?? 'Sin proyecto',
            ];
        }


        /*==================================================
        =              MÓDULOS DISPONIBLES                 =
        ==================================================*/

        $accesos = [];

        foreach ($modulos as $modulo) {

            $idSistema =
                (int) (
                    $modulo['id_sistema']
                    ?? 0
                );


            /*
             * Si el sistema padre no está activo,
             * ninguno de sus módulos puede mostrarse.
             */
            if (
                $idSistema <= 0 ||
                !isset(
                    $sistemasActivos[$idSistema]
                )
            ) {
                continue;
            }


            /*
             * Solo mostramos módulos activos.
             */
            $moduloActivo =
                (bool) (
                    $modulo['activo']
                    ?? true
                );

            if (!$moduloActivo) {
                continue;
            }


            $sistema =
                $sistemasActivos[$idSistema];


            $accesos[] = [

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


                /*==========================================
                =              DATOS PADRE                =
                ==========================================*/

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
                 * Conservamos el nombre "sistemas"
                 * temporalmente para no romper la vista.
                 *
                 * En realidad ahora contiene accesos
                 * correspondientes a módulos.
                 */
                'sistemas' =>
                    $accesos,
            ]
        );
    }
}