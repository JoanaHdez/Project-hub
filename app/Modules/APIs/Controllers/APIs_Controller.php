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
                'ruta_local' => 'C:/LaragonNuevo/laragon/www/ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',

                'headers' => [
                    [
                        'nombre' => 'Content-Type',
                        'valor' => 'application/json',
                        'obligatorio' => true,
                        'descripcion' => 'Indica que el cuerpo de la petición se envía en formato JSON.',
                    ],
                    [
                        'nombre' => 'Authorization',
                        'valor' => 'Bearer {api_token}',
                        'obligatorio' => true,
                        'descripcion' => 'Token utilizado para autenticar la petición.',
                    ],
                ],

                'parametros' => [
                    [
                        'nombre' => 'connection',
                        'tipo' => 'object',
                        'obligatorio' => true,
                        'descripcion' => 'Configuración SMTP.',
                    ],
                    [
                        'nombre' => 'to',
                        'tipo' => 'array',
                        'obligatorio' => true,
                        'descripcion' => 'Lista de destinatarios.',
                    ],
                    [
                        'nombre' => 'subject',
                        'tipo' => 'string',
                        'obligatorio' => true,
                        'descripcion' => 'Asunto del correo.',
                    ],
                ],

                // API de Invitaciones
                'ejemplo' => [
                    'metodo' => 'POST',
                    'endpoint' => '/api/correos/invitacion',
                    'url' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/api/correos/invitacion',
                    'body' => [
                        'to' => [
                            'usuario@correo.com',
                        ],
                        'subject' => 'Invitación al evento',
                        'body' => 'Contenido del correo de invitación.',
                    ],
                ],
                'respuestas' => [
                    [
                        'codigo' => 200,
                        'descripcion' => 'Correo enviado correctamente.',
                        'body' => [
                            'success' => true,
                            'message' => 'Correo enviado correctamente.'
                        ]
                    ],
                    [
                        'codigo' => 401,
                        'descripcion' => 'API Token inválido.',
                        'body' => [
                            'success' => false,
                            'message' => 'API Token inválido.'
                        ]
                    ],
                ],
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
                'ruta_local' => 'C:/LaragonNuevo/laragon/www/ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',

                'headers' => [
                    [
                        'nombre' => 'Content-Type',
                        'valor' => 'application/json',
                        'obligatorio' => true,
                        'descripcion' => 'Indica que el cuerpo de la petición se envía en formato JSON.',
                    ],
                    [
                        'nombre' => 'Authorization',
                        'valor' => 'Bearer {api_token}',
                        'obligatorio' => true,
                        'descripcion' => 'Token utilizado para autenticar la petición.',
                    ],
                ],

                'parametros' => [
                    [
                        'nombre' => 'id',
                        'tipo' => 'integer',
                        'obligatorio' => true,
                        'descripcion' => 'Identificador del registro.',
                    ],
                    [
                        'nombre' => 'correo',
                        'tipo' => 'string',
                        'obligatorio' => true,
                        'descripcion' => 'Correo electrónico del destinatario.',
                    ],
                ],

                // API Registro Exitoso
                'ejemplo' => [
                    'metodo' => 'POST',
                    'endpoint' => '/api/correos/registro',
                    'url' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/api/correos/registro',
                    'body' => [
                        'id' => 1,
                        'correo' => 'usuario@correo.com',
                    ],
                ],
                'respuestas' => [
                    [
                        'codigo' => 200,
                        'descripcion' => 'Correo enviado correctamente.',
                        'body' => [
                            'success' => true,
                            'message' => 'Correo enviado correctamente.'
                        ]
                    ],
                    [
                        'codigo' => 401,
                        'descripcion' => 'API Token inválido.',
                        'body' => [
                            'success' => false,
                            'message' => 'API Token inválido.'
                        ]
                    ],
                ],
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
                'ruta_local' => 'C:/LaragonNuevo/laragon/www/ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',

                'headers' => [
                    [
                        'nombre' => 'Content-Type',
                        'valor' => 'application/json',
                        'obligatorio' => true,
                        'descripcion' => 'Indica que el cuerpo de la petición se envía en formato JSON.',
                    ],
                    [
                        'nombre' => 'Authorization',
                        'valor' => 'Bearer {api_token}',
                        'obligatorio' => true,
                        'descripcion' => 'Token utilizado para autenticar la petición.',
                    ],
                ],

                'parametros' => [
                    [
                        'nombre' => 'id',
                        'tipo' => 'integer',
                        'obligatorio' => true,
                        'descripcion' => 'Identificador del registro.',
                    ],
                    [
                        'nombre' => 'correo',
                        'tipo' => 'string',
                        'obligatorio' => true,
                        'descripcion' => 'Correo electrónico del destinatario.',
                    ],
                ],

                // API Constancias
                'ejemplo' => [
                    'metodo' => 'POST',
                    'endpoint' => '/api/constancia/enviar-constancia',
                    'url' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/api/constancia/enviar-constancia',
                    'body' => [
                        'id' => 1,
                        'correo' => 'usuario@correo.com',
                    ],
                ],
                'respuestas' => [
                    [
                        'codigo' => 200,
                        'descripcion' => 'Correo enviado correctamente.',
                        'body' => [
                            'success' => true,
                            'message' => 'Correo enviado correctamente.'
                        ]
                    ],
                    [
                        'codigo' => 401,
                        'descripcion' => 'API Token inválido.',
                        'body' => [
                            'success' => false,
                            'message' => 'API Token inválido.'
                        ]
                    ],
                ],
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
                'ruta_local' => 'C:/LaragonNuevo/laragon/www/ExtorsionF',
                'servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public',

                'headers' => [
                    [
                        'nombre' => 'Content-Type',
                        'valor' => 'application/json',
                        'obligatorio' => true,
                        'descripcion' => 'Indica que el cuerpo de la petición se envía en formato JSON.',
                    ],
                    [
                        'nombre' => 'Authorization',
                        'valor' => 'Bearer {api_token}',
                        'obligatorio' => true,
                        'descripcion' => 'Token utilizado para autenticar la petición.',
                    ],
                ],

                'parametros' => [
                    [
                        'nombre' => 'id',
                        'tipo' => 'integer',
                        'obligatorio' => true,
                        'descripcion' => 'Identificador del registro.',
                    ],
                    [
                        'nombre' => 'correo',
                        'tipo' => 'string',
                        'obligatorio' => true,
                        'descripcion' => 'Correo electrónico del destinatario.',
                    ],
                ],

                // API Cuestionario
                'ejemplo' => [
                    'metodo' => 'POST',
                    'endpoint' => '/api/cuestionario-reforzamiento',
                    'url' => 'https://congreso.seguridadneza.gob.mx/2026/cuestionario-reforzamiento/',
                    'body' => [
                        'id' => 1,
                        'correo' => 'usuario@correo.com',
                    ],
                ],
                'respuestas' => [
                    [
                        'codigo' => 200,
                        'descripcion' => 'Correo enviado correctamente.',
                        'body' => [
                            'success' => true,
                            'message' => 'Correo enviado correctamente.'
                        ]
                    ],
                    [
                        'codigo' => 401,
                        'descripcion' => 'API Token inválido.',
                        'body' => [
                            'success' => false,
                            'message' => 'API Token inválido.'
                        ]
                    ],
                ],
            ],

        ];

        return view('App\Modules\APIs\Views\index', [
            'apis' => $apis,
        ]);
    }
}