<?php

namespace App\Modules\Proyectos\Controllers;

use App\Controllers\BaseController;

class Proyectos_Controller extends BaseController
{
    public function index()
    {
        /*
         * Datos simulados.
         *
         * Más adelante este arreglo será reemplazado por:
         *
         * $proyectos = $proyectoModel->findAll();
         */
        $proyectos = [
            [
                'id_proyecto'     => 1,
                'nombre'          => 'Proyecto Extorsión',
                'estado'          => 'Producción',
                'estado_tipo'     => 'produccion',
                'origen'          => 'Trabajo',
                'total_sistemas'  => 1,
                'fecha_creacion'  => '13/07/2026',
                'repositorio_url' => 'https://github.com/JoanaHdez/Extorsi-n',
            ],
            [
                'id_proyecto'     => 2,
                'nombre'          => 'Proyecto Eventos',
                'estado'          => 'Desarrollo',
                'estado_tipo'     => 'desarrollo',
                'origen'          => 'Trabajo',
                'total_sistemas'  => 2,
                'fecha_creacion'  => '22/06/2026',
                'repositorio_url' => 'https://github.com/JoanaHdez/Eventos',
            ],
            [
                'id_proyecto'     => 3,
                'nombre'          => 'Sistemas Comerciales',
                'estado'          => 'Desarrollo',
                'estado_tipo'     => 'desarrollo',
                'origen'          => 'Personal',
                'total_sistemas'  => 1,
                'fecha_creacion'  => '12/07/2026',
                'repositorio_url' => 'https://github.com/JoanaHdez/SistemasComerciales',
            ],
        ];

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
                    'ok' => false,
                    'mensaje' => 'No se recibieron datos del proyecto.',
                ]);
        }

        $proyecto = [
            'id_proyecto'      => time(),
            'nombre'           => trim((string) ($datos['nombre'] ?? '')),
            'estado'           => (string) ($datos['estado'] ?? ''),
            'estado_tipo'      => 'activo',
            'origen'           => (string) ($datos['origen'] ?? ''),
            'descripcion'      => trim((string) ($datos['descripcion'] ?? '')),
            'repositorio_url'  => trim((string) ($datos['repositorio_url'] ?? '')),
            'ruta_local'       => trim((string) ($datos['ruta_local'] ?? '')),
            'url_servidor'     => trim((string) ($datos['url_servidor'] ?? '')),
            'id_especificacion' => (string) ($datos['id_especificacion'] ?? ''),
            'responsable'      => trim((string) ($datos['responsable'] ?? '')),
            'observaciones'    => trim((string) ($datos['observaciones'] ?? '')),
            'total_sistemas'   => 0,
            'fecha_creacion'   => date('d/m/Y'),
        ];

        return $this->response->setJSON([
            'ok' => true,
            'mensaje' => 'Proyecto recibido correctamente.',
            'proyecto' => $proyecto,
        ]);
    }
}
