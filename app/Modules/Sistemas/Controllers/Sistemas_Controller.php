<?php

namespace App\Modules\Sistemas\Controllers;

use App\Controllers\BaseController;

class Sistemas_Controller extends BaseController
{
    public function index()
    {
        $sistemas = [
            [
                'id'       => 1,
                'nombre'   => 'Extorsión',
                'proyecto' => 'Extorsión',
                'estado'   => 'Producción',
                'url'      => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/',
            ],
            [
                'id'       => 2,
                'nombre'   => '8.º Congreso',
                'proyecto' => 'Eventos',
                'estado'   => 'Producción',
                'url'      => 'https://congreso.seguridadneza.gob.mx/Eventos/public/eventos/8vo-congreso',
            ],
            [
                'id'       => 3,
                'nombre'   => 'Project Hub',
                'proyecto' => 'Project Hub',
                'estado'   => 'Desarrollo',
                'url'      => site_url('/'),
            ],
        ];

        return view('App\Modules\Sistemas\Views\index', [
            'title'    => 'Sistemas | Project Hub',
            'sistemas' => $sistemas,
        ]);
    }
}