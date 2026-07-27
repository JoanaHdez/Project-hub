<?php

namespace App\Modules\Proyectos\Controllers;

use App\Controllers\BaseController;

class Proyectos_Controller extends BaseController
{
    public function index()
    {
        $proyectos = $this->obtenerProyectosSimulados();

        return view('App\Modules\Proyectos\Views\index', [
            'title'     => 'Proyectos | Project Hub',
            'proyectos' => $proyectos,
        ]);
    }

    public function guardar()
    {

        $datos = $this->request->getJSON(true);

        if (!$datos) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'No se recibieron datos del proyecto.',
                ]);
        }

        $estado = trim((string) ($datos['estado'] ?? ''));
        $estadoTipo = $this->obtenerTipoEstado($estado);

        $proyecto = [
            'id_proyecto'        => time(),
            'nombre'             => trim((string) ($datos['nombre'] ?? '')),
            'estado'             => $estado,
            'estado_tipo'        => $estadoTipo,
            'origen'             => trim((string) ($datos['origen'] ?? '')),
            'descripcion'        => trim((string) ($datos['descripcion'] ?? '')),
            'repositorio_url'    => trim((string) ($datos['repositorio_url'] ?? '')),
            'ruta_local'         => trim((string) ($datos['ruta_local'] ?? '')),
            'url_servidor'       => trim((string) ($datos['url_servidor'] ?? '')),
            'id_especificacion'  => (string) ($datos['id_especificacion'] ?? ''),
            'responsable'        => trim((string) ($datos['responsable'] ?? '')),
            'observaciones'      => trim((string) ($datos['observaciones'] ?? '')),
            'total_sistemas'     => 0,
            'fecha_creacion'     => date('d/m/Y'),
        ];

        return $this->response->setJSON([
            'ok'        => true,
            'mensaje'   => 'Proyecto recibido correctamente.',
            'proyecto'   => $proyecto,
            'fila_html'  => view('components/ui/fila_proyecto', [
                'proyecto' => $proyecto,
            ]),
        ]);
    }

    public function obtener(int $idProyecto)
    {
        $proyectos = $this->obtenerProyectosSimulados();

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

    private function obtenerProyectosSimulados(): array
    {
        return [
            [
                'id_proyecto'        => 1,
                'nombre'             => 'Proyecto Extorsión',
                'estado'             => 'Producción',
                'estado_tipo'        => 'produccion',
                'origen'             => 'Trabajo',
                'descripcion'        => 'Sistema para el registro y seguimiento de actividades relacionadas con extorsión.',
                'repositorio_url'    => 'https://github.com/JoanaHdez/Extorsi-n',
                'ruta_local'         => '',
                'url_servidor'       => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/',
                'id_especificacion'  => '1',
                'responsable'        => 'Joana Herrera',
                'observaciones'      => '',
                'total_sistemas'     => 1,
                'fecha_creacion'     => '13/07/2026',
            ],
            [
                'id_proyecto'        => 2,
                'nombre'             => 'Proyecto Eventos',
                'estado'             => 'Desarrollo',
                'estado_tipo'        => 'desarrollo',
                'origen'             => 'Trabajo',
                'descripcion'        => 'Proyecto para administrar micrositios, congresos y plantillas de eventos.',
                'repositorio_url'    => 'https://github.com/JoanaHdez/Eventos',
                'ruta_local'         => '',
                'url_servidor'       => '',
                'id_especificacion'  => '1',
                'responsable'        => 'Joana Herrera',
                'observaciones'      => '',
                'total_sistemas'     => 2,
                'fecha_creacion'     => '22/06/2026',
            ],
            [
                'id_proyecto'        => 3,
                'nombre'             => 'Sistemas Comerciales',
                'estado'             => 'Desarrollo',
                'estado_tipo'        => 'desarrollo',
                'origen'             => 'Personal',
                'descripcion'        => 'Proyecto para desarrollar sistemas administrativos y comerciales.',
                'repositorio_url'    => 'https://github.com/JoanaHdez/SistemasComerciales',
                'ruta_local'         => 'C:\\laragon\\www\\SistemasComerciales',
                'url_servidor'       => '',
                'id_especificacion'  => '1',
                'responsable'        => 'Joana Herrera',
                'observaciones'      => '',
                'total_sistemas'     => 1,
                'fecha_creacion'     => '12/07/2026',
            ],
        ];
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

    public function actualizar(int $id)
{
    $datos = $this->request->getJSON(true);

    if (!$datos) {
        return $this->response
            ->setStatusCode(400)
            ->setJSON([
                'ok' => false,
                'mensaje' => 'Datos inválidos.'
            ]);
    }

    // Por ahora simulamos la actualización

    return $this->response->setJSON([
        'ok' => true,
        'mensaje' => 'Proyecto actualizado correctamente.',
        'proyecto' => $datos
    ]);
}
}
