<?php

namespace App\Modules\Proyectos\Controllers;

use App\Controllers\BaseController;
use App\Modules\Proyectos\Services\Proyecto_StorageService;

class Proyectos_Controller extends BaseController
{
    private Proyecto_StorageService $storage;

    public function __construct()
    {
        $this->storage = new Proyecto_StorageService();
    }

    /**
     * Muestra el listado de proyectos.
     */
    public function index()
    {
        $proyectos = $this->storage->obtenerTodos();

        return view('App\Modules\Proyectos\Views\index', [
            'title'     => 'Proyectos | Project Hub',
            'proyectos' => $proyectos,
        ]);
    }

    /**
     * Registra un nuevo proyecto.
     */
    public function guardar()
    {
        $datos = $this->request->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'No se recibieron datos válidos del proyecto.',
                ]);
        }

        $proyectos = $this->storage->obtenerTodos();

        $estado = trim((string) ($datos['estado'] ?? ''));

        $proyecto = [
            'id_proyecto'       => $this->storage->generarNuevoId($proyectos),
            'nombre'            => trim((string) ($datos['nombre'] ?? '')),
            'estado'            => $estado,
            'estado_tipo'       => $this->obtenerTipoEstado($estado),
            'origen'            => trim((string) ($datos['origen'] ?? '')),
            'descripcion'       => trim((string) ($datos['descripcion'] ?? '')),
            'repositorio_url'   => trim((string) ($datos['repositorio_url'] ?? '')),
            'ruta_local'        => trim((string) ($datos['ruta_local'] ?? '')),
            'url_servidor'      => trim((string) ($datos['url_servidor'] ?? '')),
            'id_especificacion' => (string) ($datos['id_especificacion'] ?? ''),
            'responsable'       => trim((string) ($datos['responsable'] ?? '')),
            'observaciones'     => trim((string) ($datos['observaciones'] ?? '')),
            'total_sistemas'    => 0,
            'fecha_creacion'    => date('d/m/Y'),
        ];

        $proyectos[] = $proyecto;

        $this->storage->guardarTodos($proyectos);

        return $this->response->setJSON([
            'ok'       => true,
            'mensaje'  => 'Proyecto registrado correctamente.',
            'proyecto' => $proyecto,
            'fila_html' => view('components/ui/fila_proyecto', [
                'proyecto' => $proyecto,
            ]),
        ]);
    }

    /**
     * Devuelve un proyecto por su identificador.
     */
    public function obtener(int $idProyecto)
    {
        $proyectos = $this->storage->obtenerTodos();

        $proyectoEncontrado = null;

        foreach ($proyectos as $proyecto) {
            if ((int) ($proyecto['id_proyecto'] ?? 0) === $idProyecto) {
                $proyectoEncontrado = $proyecto;
                break;
            }
        }

        if ($proyectoEncontrado === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'No se encontró el proyecto solicitado.',
                ]);
        }

        return $this->response->setJSON([
            'ok'       => true,
            'proyecto' => $proyectoEncontrado,
        ]);
    }

    /**
     * Actualiza un proyecto existente.
     */
    public function actualizar(int $idProyecto)
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

        $proyectos = $this->storage->obtenerTodos();

        $indiceProyecto = null;
        $proyectoExistente = null;

        foreach ($proyectos as $indice => $proyecto) {
            if ((int) ($proyecto['id_proyecto'] ?? 0) === $idProyecto) {
                $indiceProyecto = $indice;
                $proyectoExistente = $proyecto;
                break;
            }
        }

        if ($proyectoExistente === null || $indiceProyecto === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'El proyecto solicitado no existe.',
                ]);
        }

        $estado = trim((string) ($datos['estado'] ?? ''));

        $proyectoActualizado = array_merge(
            $proyectoExistente,
            [
                'id_proyecto'       => $idProyecto,
                'nombre'            => trim((string) ($datos['nombre'] ?? '')),
                'estado'            => $estado,
                'estado_tipo'       => $this->obtenerTipoEstado($estado),
                'origen'            => trim((string) ($datos['origen'] ?? '')),
                'descripcion'       => trim((string) ($datos['descripcion'] ?? '')),
                'repositorio_url'   => trim((string) ($datos['repositorio_url'] ?? '')),
                'ruta_local'        => trim((string) ($datos['ruta_local'] ?? '')),
                'url_servidor'      => trim((string) ($datos['url_servidor'] ?? '')),
                'id_especificacion' => (string) ($datos['id_especificacion'] ?? ''),
                'responsable'       => trim((string) ($datos['responsable'] ?? '')),
                'observaciones'     => trim((string) ($datos['observaciones'] ?? '')),
            ]
        );

        $proyectos[$indiceProyecto] = $proyectoActualizado;

        $this->storage->guardarTodos($proyectos);

        return $this->response->setJSON([
            'ok'       => true,
            'mensaje'  => 'Proyecto actualizado correctamente.',
            'proyecto' => $proyectoActualizado,
            'fila_html' => view('components/ui/fila_proyecto', [
                'proyecto' => $proyectoActualizado,
            ]),
        ]);
    }

    /**
     * Convierte el estado visible en la clase utilizada por la interfaz.
     */
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