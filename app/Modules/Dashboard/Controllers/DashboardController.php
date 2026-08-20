<?php

namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;

use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Modulos\Services\Modulo_StorageService;
use App\Modules\APIs\Services\API_StorageService;

use App\Services\Actividad_StorageService;


class DashboardController extends BaseController
{
    private Proyecto_StorageService $proyectoStorage;
    private Sistema_StorageService $sistemaStorage;
    private Modulo_StorageService $moduloStorage;
    private API_StorageService $apiStorage;
    private Actividad_StorageService $actividadStorage;


    public function __construct()
    {
        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->moduloStorage =
            new Modulo_StorageService();

        $this->apiStorage =
            new API_StorageService();

        $this->actividadStorage =
            new Actividad_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();

        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $modulos =
            $this->moduloStorage
            ->obtenerTodos();

        $apis =
            $this->apiStorage
            ->obtenerTodos();

        $actividades =
            $this->actividadStorage
            ->obtenerRecientes(
                10
            );


        /*==================================================
        =                    VISTA                          =
        ==================================================*/

        return view(
            'App\Modules\Dashboard\Views\index',
            [
                'title' =>
                'Dashboard | Project Hub',

                'totalProyectos' =>
                count($proyectos),

                'totalSistemas' =>
                count($sistemas),

                'totalModulos' =>
                count($modulos),

                'totalApis' =>
                count($apis),

                'actividades' =>
                $actividades,
            ]
        );
    }

    /*==================================================
    =              HISTORIAL DE ACTIVIDAD              =
    ==================================================*/

    public function actividad()
    {
        $actividades =
            $this->actividadStorage
            ->obtenerTodos();

        /*
     * Mostramos primero
     * las actividades más recientes.
     */
        usort(
            $actividades,
            static function (
                array $a,
                array $b
            ): int {

                $fechaA =
                    strtotime(
                        $a['fecha_hora']
                            ?? ''
                    );

                $fechaB =
                    strtotime(
                        $b['fecha_hora']
                            ?? ''
                    );

                return $fechaB <=> $fechaA;
            }
        );

        return view(
            'App\Modules\Dashboard\Views\actividad',
            [
                'title' =>
                'Historial de actividad | Project Hub',

                'actividades' =>
                $actividades,
            ]
        );
    }
}
