<?php

namespace App\Modules\APIs\Controllers;

use App\Controllers\BaseController;
use App\Modules\APIs\Services\API_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;

class APIs_Controller extends BaseController
{
    private API_StorageService $storage;
    private Proyecto_StorageService $proyectoStorage;

    public function __construct()
    {
        $this->storage = new API_StorageService();
        $this->proyectoStorage = new Proyecto_StorageService();
    }
    public function index()
    {
        /*
     * Obtener las APIs desde el almacenamiento provisional.
     */
        $apisAlmacenadas = $this->storage->obtenerTodos();

        /*
     * Obtener los proyectos para resolver el nombre
     * a partir de id_proyecto.
     */
        $proyectos = $this->proyectoStorage->obtenerTodos();

        /*
     * Crear mapa:
     *
     * id_proyecto => nombre
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
     * Adaptar los datos del JSON a los nombres
     * que actualmente espera la vista de APIs.
     *
     * Esto permite cambiar el almacenamiento
     * sin modificar todavía la interfaz.
     */
        $apis = array_map(
            static function (array $api) use ($nombresProyectos): array {
                $idProyecto = isset($api['id_proyecto'])
                    ? (int) $api['id_proyecto']
                    : null;

                return array_merge(
                    $api,
                    [
                        /*
                     * Compatibilidad temporal:
                     * la vista actual utiliza "id".
                     */
                        'id' =>
                        $api['id_api'] ?? null,

                        /*
                     * La vista actual utiliza el nombre
                     * del proyecto directamente.
                     */
                        'proyecto' =>
                        $idProyecto !== null
                            ? (
                                $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto'
                            )
                            : 'Sin proyecto',

                        /*
                     * Compatibilidad con los nombres
                     * utilizados actualmente por la vista.
                     */
                        'repositorio' =>
                        $api['repositorio_url'] ?? '',

                        'servidor' =>
                        $api['url_servidor'] ?? '',
                    ]
                );
            },
            $apisAlmacenadas
        );

        $proyectosDisponibles = array_map(
    static function (array $proyecto): array {
        return [
            'id_proyecto' =>
                $proyecto['id_proyecto'] ?? null,

            'nombre' =>
                $proyecto['nombre'] ?? 'Proyecto sin nombre',
        ];
    },
    $proyectos
);

        return view(
    'App\Modules\APIs\Views\index',
    [
        'title'     => 'APIs | Project Hub',
        'apis'      => $apis,
        'proyectos' => $proyectosDisponibles,
    ]
);
    }
}

