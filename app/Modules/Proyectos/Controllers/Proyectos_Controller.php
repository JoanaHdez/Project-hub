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
}