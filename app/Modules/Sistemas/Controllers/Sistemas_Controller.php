<?php

namespace App\Modules\Sistemas\Controllers;

use App\Controllers\BaseController;

class Sistemas_Controller extends BaseController
{
    public function index()
    {
        $sistemas = [
            [
                'id'                  => 1,
                'nombre'              => 'Extorsión',
                'proyecto'            => 'Extorsión',
                'estado'              => 'Producción',
                'modo_visualizacion'  => 'integrado',
                'url'                 => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/',
                'repositorio'         => 'https://github.com/JoanaHdez/Extorsi-n.git',
                'ruta_local'          => 'Z:\\ExtorsionF',
                'servidor'            => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/',
            ],
            [
                'id'                  => 2,
                'nombre'              => '8.º Congreso',
                'proyecto'            => 'Eventos',
                'estado'              => 'Producción',
                'modo_visualizacion'  => 'integrado',
                'url'                 => 'https://congreso.seguridadneza.gob.mx/Eventos/public/eventos/8vo-congreso',
                'repositorio'         => '—',
                'ruta_local'          => 'Z:\\Eventos',
                'servidor'            => 'https://congreso.seguridadneza.gob.mx/Eventos/public/',
            ],
            [
                'id'                  => 3,
                'nombre'              => 'Project Hub',
                'proyecto'            => 'Project Hub',
                'estado'              => 'Desarrollo',
                'modo_visualizacion'  => 'registro',
                'url'                 => '',
                'repositorio'         => 'https://github.com/JoanaHdez/Project-hub.git',
                'ruta_local'          => 'C:\\laragon\\www\\ProjectHub',
                'servidor'            => 'Sin publicar',
            ],
        ];

        return view('App\Modules\Sistemas\Views\index', [
            'title'    => 'Sistemas | Project Hub',
            'sistemas' => $sistemas,
        ]);
    }
}