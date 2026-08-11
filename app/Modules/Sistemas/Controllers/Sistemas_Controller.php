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

    public function guardar()
    {
        $datos = $this->request->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'Los datos enviados no son válidos.',
                ]);
        }

        $idProyecto = (int) ($datos['id_proyecto'] ?? 0);

        if ($idProyecto <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'No se encontró el proyecto asociado.',
                ]);
        }

        $sistemas = $this->storage->obtenerTodos();

        $estado = trim((string) ($datos['estado'] ?? ''));

        $sistema = [
            'id_sistema'         => $this->storage->generarNuevoId($sistemas),
            'id_proyecto'        => $idProyecto,
            'nombre'             => trim((string) ($datos['nombre'] ?? '')),
            'tipo'               => trim((string) ($datos['tipo'] ?? 'Sistema')),
            'estado'             => $estado,
            'estado_tipo'        => $this->obtenerTipoEstado($estado),
            'modo_visualizacion' => trim(
                (string) ($datos['modo_visualizacion'] ?? 'registro')
            ),
            'descripcion'        => trim(
                (string) ($datos['descripcion'] ?? '')
            ),
            'url'                => trim(
                (string) ($datos['url'] ?? '')
            ),
            'repositorio_url'    => trim(
                (string) ($datos['repositorio_url'] ?? '')
            ),
            'ruta_local'         => trim(
                (string) ($datos['ruta_local'] ?? '')
            ),
            'url_servidor'       => trim(
                (string) ($datos['url_servidor'] ?? '')
            ),
            'responsable'        => trim(
                (string) ($datos['responsable'] ?? '')
            ),
            'observaciones'      => trim(
                (string) ($datos['observaciones'] ?? '')
            ),
            'activo'             => true,
        ];

        $sistemas[] = $sistema;

        $this->storage->guardarTodos($sistemas);

        $totalSistemasProyecto = count($this->storage->obtenerPorProyecto($idProyecto));

        return $this->response->setJSON([
            'ok'             => true,
            'mensaje'        => 'Sistema registrado correctamente.',
            'sistema'        => $sistema,
            'total_sistemas' => $totalSistemasProyecto,
        ]);
    }

    private function obtenerTipoEstado(string $estado): string
    {
        return match ($estado) {
            'Producción'    => 'produccion',
            'Desarrollo'    => 'desarrollo',
            'Detenido'      => 'detenido',
            'Mantenimiento' => 'mantenimiento',
            default         => 'inactivo',
        };
    }
}
