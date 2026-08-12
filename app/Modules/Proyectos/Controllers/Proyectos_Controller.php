<?php

namespace App\Modules\Proyectos\Controllers;

use App\Controllers\BaseController;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Sistemas\Services\Sistema_StorageService;

class Proyectos_Controller extends BaseController
{
    private Proyecto_StorageService $storage;
    private Sistema_StorageService $sistemaStorage;

    public function __construct()
    {
        $this->storage = new Proyecto_StorageService();
        $this->sistemaStorage = new Sistema_StorageService();
    }

    public function index()
    {
        $proyectos = $this->storage->obtenerTodos();
        $sistemas = $this->sistemaStorage->obtenerTodos();

        /*
         * Contar cuántos sistemas pertenecen
         * a cada proyecto.
         */
        $totalesPorProyecto = [];

        foreach ($sistemas as $sistema) {
            $idProyecto = (int) (
                $sistema['id_proyecto'] ?? 0
            );

            if ($idProyecto <= 0) {
                continue;
            }

            if (!isset($totalesPorProyecto[$idProyecto])) {
                $totalesPorProyecto[$idProyecto] = 0;
            }

            $totalesPorProyecto[$idProyecto]++;
        }

        /*
         * Asignar el total real a cada proyecto.
         */
        foreach ($proyectos as &$proyecto) {
            $idProyecto = (int) (
                $proyecto['id_proyecto'] ?? 0
            );

            $proyecto['total_sistemas'] =
                $totalesPorProyecto[$idProyecto] ?? 0;
        }

        unset($proyecto);

        return view(
            'App\Modules\Proyectos\Views\index',
            [
                'title'     => 'Proyectos | Project Hub',
                'proyectos' => $proyectos,
            ]
        );
    }

    public function guardar()
    {
        $datos = $this->request->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                        'No se recibieron datos válidos del proyecto.',
                ]);
        }

        $proyectos = $this->storage->obtenerTodos();

        $estado = trim(
            (string) ($datos['estado'] ?? '')
        );

        $proyecto = [
            'id_proyecto' =>
                $this->storage->generarNuevoId(
                    $proyectos
                ),

            'nombre' => trim(
                (string) ($datos['nombre'] ?? '')
            ),

            'estado' => $estado,

            'estado_tipo' =>
                $this->obtenerTipoEstado($estado),

            'origen' => trim(
                (string) ($datos['origen'] ?? '')
            ),

            'descripcion' => trim(
                (string) ($datos['descripcion'] ?? '')
            ),

            'repositorio_url' => trim(
                (string) (
                    $datos['repositorio_url']
                    ?? ''
                )
            ),

            'ruta_local' => trim(
                (string) ($datos['ruta_local'] ?? '')
            ),

            'url_servidor' => trim(
                (string) (
                    $datos['url_servidor']
                    ?? ''
                )
            ),

            'id_especificacion' => (string) (
                $datos['id_especificacion'] ?? ''
            ),

            'responsable' => trim(
                (string) (
                    $datos['responsable']
                    ?? ''
                )
            ),

            'observaciones' => trim(
                (string) (
                    $datos['observaciones']
                    ?? ''
                )
            ),

            /*
             * Al crear un proyecto todavía
             * no tiene sistemas asociados.
             */
            'total_sistemas' => 0,

            'fecha_creacion' => date('d/m/Y'),
        ];

        $proyectos[] = $proyecto;

        $this->storage->guardarTodos($proyectos);

        return $this->response->setJSON([
            'ok' => true,

            'mensaje' =>
                'Proyecto registrado correctamente.',

            'proyecto' => $proyecto,

            'fila_html' => view(
                'components/ui/fila_proyecto',
                [
                    'proyecto' => $proyecto,
                ],
                [
                    'saveData' => false,
                ]
            ),
        ]);
    }

    public function obtener(int $idProyecto)
    {
        $proyectos = $this->storage->obtenerTodos();

        $proyectoEncontrado = null;

        foreach ($proyectos as $proyecto) {
            if (
                (int) (
                    $proyecto['id_proyecto'] ?? 0
                ) === $idProyecto
            ) {
                $proyectoEncontrado = $proyecto;
                break;
            }
        }

        if ($proyectoEncontrado === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }

        /*
         * Obtener también el total real de sistemas.
         */
        $proyectoEncontrado['total_sistemas'] =
            count(
                $this->sistemaStorage
                    ->obtenerPorProyecto(
                        $idProyecto
                    )
            );

        return $this->response->setJSON([
            'ok'       => true,
            'proyecto' => $proyectoEncontrado,
        ]);
    }

    public function actualizar(int $idProyecto)
    {
        $datos = $this->request->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }

        $proyectos = $this->storage->obtenerTodos();

        $indiceProyecto = null;
        $proyectoExistente = null;

        foreach (
            $proyectos
            as $indice => $proyecto
        ) {
            if (
                (int) (
                    $proyecto['id_proyecto'] ?? 0
                ) === $idProyecto
            ) {
                $indiceProyecto = $indice;
                $proyectoExistente = $proyecto;
                break;
            }
        }

        if (
            $proyectoExistente === null
            || $indiceProyecto === null
        ) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' =>
                        'El proyecto solicitado no existe.',
                ]);
        }

        $estado = trim(
            (string) ($datos['estado'] ?? '')
        );

        /*
         * El total de sistemas no viene del formulario.
         * Se obtiene de la relación real.
         */
        $totalSistemas = count(
            $this->sistemaStorage
                ->obtenerPorProyecto(
                    $idProyecto
                )
        );

        $proyectoActualizado = array_merge(
            $proyectoExistente,
            [
                'id_proyecto' => $idProyecto,

                'nombre' => trim(
                    (string) (
                        $datos['nombre']
                        ?? ''
                    )
                ),

                'estado' => $estado,

                'estado_tipo' =>
                    $this->obtenerTipoEstado(
                        $estado
                    ),

                'origen' => trim(
                    (string) (
                        $datos['origen']
                        ?? ''
                    )
                ),

                'descripcion' => trim(
                    (string) (
                        $datos['descripcion']
                        ?? ''
                    )
                ),

                'repositorio_url' => trim(
                    (string) (
                        $datos['repositorio_url']
                        ?? ''
                    )
                ),

                'ruta_local' => trim(
                    (string) (
                        $datos['ruta_local']
                        ?? ''
                    )
                ),

                'url_servidor' => trim(
                    (string) (
                        $datos['url_servidor']
                        ?? ''
                    )
                ),

                'id_especificacion' =>
                    (string) (
                        $datos['id_especificacion']
                        ?? ''
                    ),

                'responsable' => trim(
                    (string) (
                        $datos['responsable']
                        ?? ''
                    )
                ),

                'observaciones' => trim(
                    (string) (
                        $datos['observaciones']
                        ?? ''
                    )
                ),

                'total_sistemas' =>
                    $totalSistemas,
            ]
        );

        $proyectos[$indiceProyecto] =
            $proyectoActualizado;

        $this->storage->guardarTodos($proyectos);

        return $this->response->setJSON([
            'ok' => true,

            'mensaje' =>
                'Proyecto actualizado correctamente.',

            'proyecto' =>
                $proyectoActualizado,

            'fila_html' => view(
                'components/ui/fila_proyecto',
                [
                    'proyecto' =>
                        $proyectoActualizado,
                ],
                [
                    'saveData' => false,
                ]
            ),
        ]);
    }

    public function desactivar(int $idProyecto)
    {
        $proyecto =
            $this->storage->desactivar(
                $idProyecto
            );

        if ($proyecto === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }

        return $this->response->setJSON([
            'ok'       => true,
            'mensaje'  =>
                'Proyecto desactivado correctamente.',
            'proyecto' => $proyecto,
        ]);
    }

    public function activar(int $idProyecto)
    {
        $proyecto =
            $this->storage->activar(
                $idProyecto
            );

        if ($proyecto === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }

        return $this->response->setJSON([
            'ok'       => true,
            'mensaje'  =>
                'Proyecto activado correctamente.',
            'proyecto' => $proyecto,
        ]);
    }

    public function eliminar(int $idProyecto)
    {
        $eliminado =
            $this->storage->eliminar(
                $idProyecto
            );

        if (!$eliminado) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }

        return $this->response->setJSON([
            'ok'      => true,
            'mensaje' =>
                'Proyecto eliminado correctamente.',
        ]);
    }

    private function obtenerTipoEstado(
        string $estado
    ): string {
        return match ($estado) {
            'Producción' =>
                'produccion',

            'Desarrollo' =>
                'desarrollo',

            'Detenido' =>
                'detenido',

            'Mantenimiento' =>
                'mantenimiento',

            default =>
                'inactivo',
        };
    }
}