<?php

namespace App\Modules\APIs\Controllers;

use App\Controllers\BaseController;
use App\Modules\APIs\Services\API_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;

class APIs_Controller extends BaseController
{
    private API_StorageService $storage;
    private Proyecto_StorageService $proyectoStorage;

    public function __construct()
    {
        $this->storage =
            new API_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $apisAlmacenadas =
            $this->storage->obtenerTodos();

        usort(
            $apisAlmacenadas,
            static function (
                array $a,
                array $b
            ): int {
                return (int) (
                    $b['id_api'] ?? 0
                ) <=> (int) (
                    $a['id_api'] ?? 0
                );
            }
        );

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {
            $idProyecto = (int) (
                $proyecto['id_proyecto']
                ?? 0
            );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[$idProyecto] =
                $proyecto['nombre']
                ?? 'Sin proyecto';
        }

        $apis = array_map(
            static function (
                array $api
            ) use (
                $nombresProyectos
            ): array {
                $idProyecto =
                    isset(
                        $api['id_proyecto']
                    )
                    ? (int) $api['id_proyecto']
                    : null;

                return array_merge(
                    $api,
                    [
                        'id' =>
                        $api['id_api']
                            ?? null,

                        'proyecto' =>
                        $idProyecto !== null
                            ? (
                                $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto'
                            )
                            : 'Sin proyecto',

                        'repositorio' =>
                        $api['repositorio_url'] ?? '',

                        'servidor' =>
                        $api['url_servidor'] ?? '',
                    ]
                );
            },
            $apisAlmacenadas
        );

        $proyectosDisponibles =
            array_map(
                static function (
                    array $proyecto
                ): array {
                    return [
                        'id_proyecto' =>
                        $proyecto['id_proyecto'] ?? null,

                        'nombre' =>
                        $proyecto['nombre']
                            ?? 'Proyecto sin nombre',
                    ];
                },
                $proyectos
            );

        return view(
            'App\Modules\APIs\Views\index',
            [
                'title' =>
                'APIs | Project Hub',

                'apis' =>
                $apis,

                'proyectos' =>
                $proyectosDisponibles,
            ]
        );
    }


    /*==================================================
    =                    GUARDAR                       =
    ==================================================*/

    public function guardar()
    {
        $datos =
            $this->request
            ->getJSON(true);

        $error =
            $this->validarDatosApi(
                $datos
            );

        if ($error !== null) {
            return $error;
        }

        $apis =
            $this->storage
            ->obtenerTodos();

        $api =
            $this->construirDatosApi(
                $datos,
                [
                    'id_api' =>
                    $this->storage
                        ->generarNuevoId(
                            $apis
                        ),

                    'activo' => true,
                ]
            );

        $apis[] = $api;

        $this->storage
            ->guardarTodos(
                $apis
            );

        $apiVista =
            $this->construirApiVista(
                $api
            );

        $selectorHtml =
            $this->construirSelectorHtml(
                $apiVista
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'API registrada correctamente.',

                'api' =>
                $apiVista,

                'selector_html' =>
                $selectorHtml,
            ]);
    }


    /*==================================================
    =                   ACTUALIZAR                     =
    ==================================================*/

    public function actualizar(
        int $idApi
    ) {
        $datos =
            $this->request
            ->getJSON(true);

        $error =
            $this->validarDatosApi(
                $datos
            );

        if ($error !== null) {
            return $error;
        }

        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;
        $apiExistente = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                $apiExistente =
                    $api;

                break;
            }
        }

        if (
            $indiceApi === null ||
            $apiExistente === null
        ) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $apiActualizada =
            $this->construirDatosApi(
                $datos,
                [
                    'id_api' =>
                    $idApi,

                    'activo' =>
                    (bool) (
                        $apiExistente['activo'] ?? true
                    ),

                    'arquitectura' =>
                    $apiExistente['arquitectura'] ?? [],

                    'dependencias' =>
                    $apiExistente['dependencias'] ?? [],
                ]
            );

        $apis[$indiceApi] =
            $apiActualizada;

        $this->storage
            ->guardarTodos(
                $apis
            );

        $apiVista =
            $this->construirApiVista(
                $apiActualizada
            );

        $selectorHtml =
            $this->construirSelectorHtml(
                $apiVista
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'API actualizada correctamente.',

                'api' =>
                $apiVista,

                'selector_html' =>
                $selectorHtml,
            ]);
    }


    /*==================================================
    =             ACTUALIZAR ARQUITECTURA              =
    ==================================================*/

    public function actualizarArquitectura(
        int $idApi
    ) {
        $datos =
            $this->request
            ->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'Los datos enviados no son válidos.',
                ]);
        }

        $arquitectura =
            $datos['arquitectura']
            ?? null;

        if (!is_array($arquitectura)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'La arquitectura enviada no es válida.',
                ]);
        }

        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                break;
            }
        }

        if ($indiceApi === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $apis[$indiceApi]['arquitectura'] = [
            'modulo' =>
            trim(
                (string) (
                    $arquitectura['modulo']
                    ?? ''
                )
            ),

            'componentes' =>
            is_array(
                $arquitectura['componentes']
                    ?? null
            )
                ? $arquitectura['componentes']
                : [],
        ];

        $this->storage
            ->guardarTodos(
                $apis
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Arquitectura guardada correctamente.',

                'arquitectura' =>
                $apis[$indiceApi]['arquitectura'],
            ]);
    }


    /*==================================================
    =             ACTUALIZAR DEPENDENCIAS              =
    ==================================================*/

    public function actualizarDependencias(
        int $idApi
    ) {
        $datos =
            $this->request
            ->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'Los datos enviados no son válidos.',
                ]);
        }

        $dependencias =
            $datos['dependencias']
            ?? null;

        if (!is_array($dependencias)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'Las dependencias enviadas no son válidas.',
                ]);
        }

        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                break;
            }
        }

        if ($indiceApi === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $dependenciasNormalizadas = [];

        foreach (
            $dependencias as
            $dependencia
        ) {
            if (!is_array($dependencia)) {
                continue;
            }

            $tipo =
                trim(
                    (string) (
                        $dependencia['tipo']
                        ?? ''
                    )
                );

            $nombre =
                trim(
                    (string) (
                        $dependencia['nombre']
                        ?? ''
                    )
                );

            $descripcion =
                trim(
                    (string) (
                        $dependencia['descripcion']
                        ?? ''
                    )
                );

            $estado =
                trim(
                    (string) (
                        $dependencia['estado']
                        ?? ''
                    )
                );

            if (
                $tipo === '' &&
                $nombre === '' &&
                $descripcion === '' &&
                $estado === ''
            ) {
                continue;
            }

            $dependenciasNormalizadas[] = [
                'tipo' =>
                $tipo,

                'nombre' =>
                $nombre,

                'descripcion' =>
                $descripcion,

                'estado' =>
                $estado,
            ];
        }

        $apis[$indiceApi]['dependencias'] =
            $dependenciasNormalizadas;

        $this->storage
            ->guardarTodos(
                $apis
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Dependencias guardadas correctamente.',

                'dependencias' =>
                $apis[$indiceApi]['dependencias'],
            ]);
    }


    /*==================================================
    =             ACTUALIZAR OBSERVACIONES              =
    ==================================================*/

    public function actualizarObservaciones(
        int $idApi
    ) {
        $datos =
            $this->request
            ->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'Los datos enviados no son válidos.',
                ]);
        }

        $observaciones =
            $datos['observaciones']
            ?? null;

        if (!is_array($observaciones)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'Las observaciones enviadas no son válidas.',
                ]);
        }

        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                break;
            }
        }

        if ($indiceApi === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }


        /*==================================================
        =          NORMALIZAR OBSERVACIONES               =
        ==================================================*/

        $observacionesNormalizadas = [];

        foreach (
            $observaciones as
            $observacion
        ) {
            if (!is_array($observacion)) {
                continue;
            }

            $tipo =
                trim(
                    (string) (
                        $observacion['tipo']
                        ?? ''
                    )
                );

            $mensaje =
                trim(
                    (string) (
                        $observacion['mensaje']
                        ?? ''
                    )
                );

            /*
         * Ignorar observaciones
         * completamente vacías.
         */
            if (
                $tipo === '' &&
                $mensaje === ''
            ) {
                continue;
            }

            $observacionesNormalizadas[] = [
                'tipo' =>
                $tipo,

                'mensaje' =>
                $mensaje,
            ];
        }


        /*==================================================
        =                  GUARDAR                        =
        ==================================================*/

        $apis[$indiceApi]['observaciones'] =
            $observacionesNormalizadas;

        $this->storage
            ->guardarTodos(
                $apis
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Observaciones guardadas correctamente.',

                'observaciones' =>
                $apis[$indiceApi]['observaciones'],
            ]);
    }


    /*==================================================
    =                  DESACTIVAR API                   =
    ==================================================*/

    public function desactivar(
        int $idApi
    ) {
        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;
        $apiEncontrada = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                $apiEncontrada =
                    $api;

                break;
            }
        }

        if (
            $indiceApi === null ||
            $apiEncontrada === null
        ) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $apiEncontrada['activo'] =
            false;

        $apis[$indiceApi] =
            $apiEncontrada;

        $this->storage
            ->guardarTodos(
                $apis
            );

        $apiVista =
            $this->construirApiVista(
                $apiEncontrada
            );

        $selectorHtml =
            $this->construirSelectorHtml(
                $apiVista
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'API desactivada correctamente.',

                'api' =>
                $apiVista,

                'selector_html' =>
                $selectorHtml,
            ]);
    }


    /*==================================================
    =                    ACTIVAR API                    =
    ==================================================*/

    public function activar(
        int $idApi
    ) {
        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;
        $apiEncontrada = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                $apiEncontrada =
                    $api;

                break;
            }
        }

        if (
            $indiceApi === null ||
            $apiEncontrada === null
        ) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $apiEncontrada['activo'] =
            true;

        $apis[$indiceApi] =
            $apiEncontrada;

        $this->storage
            ->guardarTodos(
                $apis
            );

        $apiVista =
            $this->construirApiVista(
                $apiEncontrada
            );

        $selectorHtml =
            $this->construirSelectorHtml(
                $apiVista
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'API activada correctamente.',

                'api' =>
                $apiVista,

                'selector_html' =>
                $selectorHtml,
            ]);
    }


    /*==================================================
    =                    ELIMINAR API                    =
    ==================================================*/

    public function eliminar(
        int $idApi
    ) {
        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;
        $apiEncontrada = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                $apiEncontrada =
                    $api;

                break;
            }
        }

        if (
            $indiceApi === null ||
            $apiEncontrada === null
        ) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        /*
     * Eliminar la API del arreglo.
     */
        array_splice(
            $apis,
            $indiceApi,
            1
        );

        $this->storage
            ->guardarTodos(
                $apis
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'API eliminada correctamente.',

                'id_api' =>
                $idApi,

                'total_apis' =>
                count($apis),
            ]);
    }


    /*==================================================
    =                VALIDAR DATOS                    =
    ==================================================*/

    private function validarDatosApi(
        mixed $datos
    ) {
        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'Los datos enviados no son válidos.',
                ]);
        }

        $idProyecto = (int) (
            $datos['id_proyecto']
            ?? 0
        );

        if ($idProyecto <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'Debes seleccionar un proyecto.',
                ]);
        }

        $nombre = trim(
            (string) (
                $datos['nombre']
                ?? ''
            )
        );

        if ($nombre === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'El nombre de la API es obligatorio.',
                ]);
        }

        $estado = trim(
            (string) (
                $datos['estado']
                ?? ''
            )
        );

        if ($estado === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'El estado es obligatorio.',
                ]);
        }

        $metodo = trim(
            (string) (
                $datos['metodo']
                ?? ''
            )
        );

        if ($metodo === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'El método HTTP es obligatorio.',
                ]);
        }

        $endpoint = trim(
            (string) (
                $datos['endpoint']
                ?? ''
            )
        );

        if ($endpoint === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'El endpoint es obligatorio.',
                ]);
        }

        return null;
    }


    /*==================================================
    =             CONSTRUIR DATOS API                =
    ==================================================*/

    private function construirDatosApi(
        array $datos,
        array $datosBase = []
    ): array {
        $idSistema =
            $datos['id_sistema']
            ?? null;

        if (
            $idSistema === ''
            || $idSistema === null
        ) {
            $idSistema = null;
        } else {
            $idSistema =
                (int) $idSistema;
        }

        return array_merge(
            $datosBase,
            [
                'id_proyecto' =>
                (int) (
                    $datos['id_proyecto'] ?? 0
                ),

                'id_sistema' =>
                $idSistema,

                'nombre' =>
                trim(
                    (string) (
                        $datos['nombre']
                        ?? ''
                    )
                ),

                'descripcion' =>
                trim(
                    (string) (
                        $datos['descripcion'] ?? ''
                    )
                ),

                'estado' =>
                trim(
                    (string) (
                        $datos['estado']
                        ?? ''
                    )
                ),

                'metodo' =>
                strtoupper(
                    trim(
                        (string) (
                            $datos['metodo']
                            ?? ''
                        )
                    )
                ),

                'endpoint' =>
                trim(
                    (string) (
                        $datos['endpoint']
                        ?? ''
                    )
                ),

                'url' =>
                trim(
                    (string) (
                        $datos['url']
                        ?? ''
                    )
                ),

                'autenticacion' =>
                trim(
                    (string) (
                        $datos['autenticacion'] ?? ''
                    )
                ),

                'repositorio_url' =>
                trim(
                    (string) (
                        $datos['repositorio_url'] ?? ''
                    )
                ),

                'ruta_local' =>
                trim(
                    (string) (
                        $datos['ruta_local'] ?? ''
                    )
                ),

                'url_servidor' =>
                trim(
                    (string) (
                        $datos['url_servidor'] ?? ''
                    )
                ),

                'headers' =>
                is_array(
                    $datos['headers']
                        ?? null
                )
                    ? $datos['headers']
                    : [],

                'parametros' =>
                is_array(
                    $datos['parametros'] ?? null
                )
                    ? $datos['parametros']
                    : [],

                'ejemplo' =>
                is_array(
                    $datos['ejemplo']
                        ?? null
                )
                    ? $datos['ejemplo']
                    : [],

                'respuestas' =>
                is_array(
                    $datos['respuestas'] ?? null
                )
                    ? $datos['respuestas']
                    : [],

                'responsable' =>
                trim(
                    (string) (
                        $datos['responsable'] ?? ''
                    )
                ),

                'observaciones' =>
                trim(
                    (string) (
                        $datos['observaciones'] ?? ''
                    )
                ),

                'arquitectura' =>
                is_array(
                    $datos['arquitectura']
                        ?? ($datosBase['arquitectura'] ?? null)
                )
                    ? (
                        $datos['arquitectura']
                        ?? $datosBase['arquitectura']
                    )
                    : [],

                'dependencias' =>
                is_array(
                    $datos['dependencias']
                        ?? ($datosBase['dependencias'] ?? null)
                )
                    ? (
                        $datos['dependencias']
                        ?? $datosBase['dependencias']
                    )
                    : [],
            ]
        );
    }


    /*==================================================
    =               CONSTRUIR API VISTA              =
    ==================================================*/

    private function construirApiVista(
        array $api
    ): array {
        $nombreProyecto =
            'Sin proyecto';

        $idProyecto =
            (int) (
                $api['id_proyecto']
                ?? 0
            );

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();

        foreach (
            $proyectos as
            $proyecto
        ) {
            if (
                (int) (
                    $proyecto['id_proyecto'] ?? 0
                ) === $idProyecto
            ) {
                $nombreProyecto =
                    $proyecto['nombre']
                    ?? 'Sin proyecto';

                break;
            }
        }

        return array_merge(
            $api,
            [
                'id' =>
                $api['id_api']
                    ?? null,

                'proyecto' =>
                $nombreProyecto,

                'repositorio' =>
                $api['repositorio_url'] ?? '',

                'servidor' =>
                $api['url_servidor'] ?? '',
            ]
        );
    }


    /*==================================================
    =             CONSTRUIR SELECTOR HTML              =
    ==================================================*/

    private function construirSelectorHtml(
        array $api
    ): string {
        return view(
            'App\Modules\APIs\Views\components\api_selector',
            [
                'titulo' =>
                $api['nombre'],

                'proyecto' =>
                $api['proyecto'],

                'estado' =>
                $api['estado'],

                'metodo' =>
                $api['metodo'],

                'atributos' => [
                    'data-api-id' =>
                    $api['id'],

                    'data-api-activo' =>
                    !empty($api['activo'])
                        ? '1'
                        : '0',

                    'data-api-id-proyecto' =>
                    $api['id_proyecto'] ?? '',

                    'data-api-id-sistema' =>
                    $api['id_sistema'] ?? '',

                    'data-api-nombre' =>
                    $api['nombre'],

                    'data-api-proyecto' =>
                    $api['proyecto'],

                    'data-api-descripcion' =>
                    $api['descripcion'],

                    'data-api-estado' =>
                    $api['estado'],

                    'data-api-metodo' =>
                    $api['metodo'],

                    'data-api-endpoint' =>
                    $api['endpoint'],

                    'data-api-url' =>
                    $api['url'],

                    'data-api-autenticacion' =>
                    $api['autenticacion'],

                    'data-api-repositorio' =>
                    $api['repositorio'],

                    'data-api-ruta' =>
                    $api['ruta_local'],

                    'data-api-servidor' =>
                    $api['servidor'],

                    'data-api-headers' =>
                    json_encode(
                        $api['headers'] ?? [],
                        JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-parametros' =>
                    json_encode(
                        $api['parametros'] ?? [],
                        JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-ejemplo' =>
                    json_encode(
                        $api['ejemplo'] ?? [],
                        JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-respuestas' =>
                    json_encode(
                        $api['respuestas'] ?? [],
                        JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-arquitectura' =>
                    json_encode(
                        $api['arquitectura'] ?? [],
                        JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-dependencias' =>
                    json_encode(
                        $api['dependencias'] ?? [],
                        JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-observaciones' =>
                    json_encode(
                        $api['observaciones'] ?? [],
                        JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                    ),
                ],
            ],
            [
                'saveData' =>
                false,
            ]
        );
    }


    /*==================================================
    =             ELIMINAR ARQUITECTURA                =
    ==================================================*/

    public function eliminarArquitectura(
        int $idApi
    ) {
        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                break;
            }
        }

        if ($indiceApi === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $apis[$indiceApi]['arquitectura'] = [
            'modulo' => '',
            'componentes' => [],
        ];

        $this->storage
            ->guardarTodos(
                $apis
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Arquitectura eliminada correctamente.',

                'arquitectura' => [
                    'modulo' => '',
                    'componentes' => [],
                ],
            ]);
    }


    /*==================================================
    =             ELIMINAR DEPENDENCIAS                =
    ==================================================*/

    public function eliminarDependencias(
        int $idApi
    ) {
        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                break;
            }
        }

        if ($indiceApi === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $apis[$indiceApi]['dependencias'] = [];

        $this->storage
            ->guardarTodos(
                $apis
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Dependencias eliminadas correctamente.',

                'dependencias' => [],
            ]);
    }

    /*==================================================
    =             ELIMINAR OBSERVACIONES               =
    ==================================================*/

    public function eliminarObservaciones(
        int $idApi
    ) {
        $apis =
            $this->storage
            ->obtenerTodos();

        $indiceApi = null;

        foreach (
            $apis as
            $indice => $api
        ) {
            if (
                (int) (
                    $api['id_api']
                    ?? 0
                ) === $idApi
            ) {
                $indiceApi =
                    $indice;

                break;
            }
        }

        if ($indiceApi === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró la API solicitada.',
                ]);
        }

        $apis[$indiceApi]['observaciones'] = [];

        $this->storage
            ->guardarTodos(
                $apis
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Observaciones eliminadas correctamente.',

                'observaciones' => [],
            ]);
    }
}
