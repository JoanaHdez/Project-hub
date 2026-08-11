<?php

namespace App\Modules\Sistemas\Controllers;

use App\Controllers\BaseController;
use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;

class Sistemas_Controller extends BaseController
{
    private Sistema_StorageService $storage;
    private Proyecto_StorageService $proyectoStorage;

    public function __construct()
    {
        $this->storage = new Sistema_StorageService();
        $this->proyectoStorage = new Proyecto_StorageService();
    }

    public function index()
    {
        /*
         * Obtener sistemas desde la fuente provisional.
         */
        $sistemasAlmacenados = $this->storage->obtenerTodos();

        /*
         * Obtener proyectos para resolver el nombre
         * a partir de id_proyecto.
         */
        $proyectos = $this->proyectoStorage->obtenerTodos();

        /*
         * Crear un mapa:
         *
         * id_proyecto => nombre
         *
         * Ejemplo:
         * 1 => Proyecto Extorsión
         * 2 => Proyecto Eventos
         */
        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {
            $idProyecto = (int) (
                $proyecto['id_proyecto'] ?? 0
            );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[$idProyecto] =
                $proyecto['nombre'] ?? 'Sin proyecto';
        }

        /*
         * Adaptar temporalmente los nombres de campos
         * para conservar compatibilidad con la vista
         * actual de Sistemas.
         */
        $sistemas = array_map(
            static function (array $sistema) use ($nombresProyectos): array {
                $idProyecto = isset($sistema['id_proyecto'])
                    ? (int) $sistema['id_proyecto']
                    : null;

                return [
                    'id' =>
                        $sistema['id_sistema'] ?? null,

                    'id_proyecto' =>
                        $idProyecto,

                    'nombre' =>
                        $sistema['nombre']
                        ?? 'Sistema sin nombre',

                    'proyecto' =>
                        $idProyecto !== null
                            ? (
                                $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto'
                            )
                            : 'Sin proyecto',

                    'estado' =>
                        $sistema['estado']
                        ?? 'Sin estado',

                    'modo_visualizacion' =>
                        $sistema['modo_visualizacion']
                        ?? 'registro',

                    'url' =>
                        $sistema['url']
                        ?? '',

                    'repositorio' =>
                        $sistema['repositorio_url']
                        ?? '',

                    'ruta_local' =>
                        $sistema['ruta_local']
                        ?? '',

                    'servidor' =>
                        $sistema['url_servidor']
                        ?? '',
                ];
            },
            $sistemasAlmacenados
        );

        return view(
            'App\Modules\Sistemas\Views\index',
            [
                'title'    => 'Sistemas | Project Hub',
                'sistemas' => $sistemas,
            ]
        );
    }

    public function obtenerPorProyecto(int $idProyecto)
{
    $sistemas = $this->storage->obtenerPorProyecto(
        $idProyecto
    );

    return $this->response->setJSON([
        'ok'       => true,
        'sistemas' => $sistemas,
        'total'    => count($sistemas),
    ]);
}
}