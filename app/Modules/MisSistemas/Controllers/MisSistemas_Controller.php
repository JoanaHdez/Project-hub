<?php

namespace App\Modules\MisSistemas\Controllers;

use App\Controllers\BaseController;
use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;

class MisSistemas_Controller extends BaseController
{
    private Sistema_StorageService $sistemaStorage;
    private Proyecto_StorageService $proyectoStorage;


    public function __construct()
    {
        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();
    }


    public function index()
    {
        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();


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


        $sistemasVista =
            array_values(
                array_filter(
                    array_map(
                        static function (
                            array $sistema
                        ) use (
                            $nombresProyectos
                        ): array {

                            $idProyecto =
                                (int) (
                                    $sistema['id_proyecto']
                                    ?? 0
                                );

                            return array_merge(
                                $sistema,
                                [
                                    'proyecto_nombre' =>
                                        $nombresProyectos[$idProyecto]
                                        ?? 'Sin proyecto',
                                ]
                            );
                        },
                        $sistemas
                    ),
                    static fn(array $sistema): bool =>
                        (bool) (
                            $sistema['activo']
                            ?? true
                        )
                )
            );


        return view(
            'App\Modules\MisSistemas\Views\index',
            [
                'title' =>
                    'Mis sistemas | Project Hub',

                'sistemas' =>
                    $sistemasVista,
            ]
        );
    }
}