<?php

namespace App\Modules\APIs\Controllers;

use App\Controllers\BaseController;

class APIs_Controller extends BaseController
{
    public function index()
    {
        $apis = [

            [
                'id' => 1,
                'nombre' => 'API de Invitaciones',
                'proyecto' => 'Extorsión',
                'descripcion' => 'Envía invitaciones por correo electrónico.',
                'estado' => 'Producción',

                'metodo' => 'POST',
                'endpoint' => '/api/correos/invitacion',
                'url' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/api/correos/invitacion',

                'autenticacion' => 'API Token',

                'repositorio' => 'https://github.com/JoanaHdez/Extorsi-n',
                'ruta_local' => 'C:\LaragonNuevo\laragon\www\ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',
            ],

            [
                'id' => 2,
                'nombre' => 'API Registro Exitoso',
                'proyecto' => 'Extorsión',
                'descripcion' => 'Envía el correo de registro exitoso.',
                'estado' => 'Producción',

                'metodo' => 'POST',
                'endpoint' => '/api/correos/registro',
                'url' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/api/correos/registro',

                'autenticacion' => 'API Token',

                'repositorio' => 'https://github.com/JoanaHdez/Extorsi-n',
                'ruta_local' => 'C:\LaragonNuevo\laragon\www\ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',
            ],

            [
                'id' => 3,
                'nombre' => 'API Constancias',
                'proyecto' => 'Extorsión',
                'descripcion' => 'Envía la constancia de acreditación.',
                'estado' => 'Producción',

                'metodo' => 'POST',
                'endpoint' => '/api/constancia/enviar-constancia',
                'url' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/api/constancia/enviar-constancia',

                'autenticacion' => 'API Token',

                'repositorio' => 'https://github.com/JoanaHdez/Extorsi-n',
                'ruta_local' => 'C:\LaragonNuevo\laragon\www\ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',
            ],

            [
                'id' => 4,
                'nombre' => 'API Cuestionario',
                'proyecto' => 'Extorsión',
                'descripcion' => 'Envía el acceso al cuestionario de reforzamiento.',
                'estado' => 'Producción',

                'metodo' => 'POST',
                'endpoint' => '/api/cuestionario-reforzamiento',
                'url' => 'https://congreso.seguridadneza.gob.mx/2026/cuestionario-reforzamiento/',

                'autenticacion' => 'API Token',

                'repositorio' => 'https://github.com/JoanaHdez/Extorsi-n',
                'ruta_local' => 'C:\LaragonNuevo\laragon\www\ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',
            ],

        ];

        return view('App\Modules\APIs\Views\index', [
            'apis' => $apis,
        ]);
    }
}