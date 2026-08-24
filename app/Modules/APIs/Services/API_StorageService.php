<?php

namespace App\Modules\APIs\Services;

use App\Modules\APIs\Models\API_Model;
use App\Modules\APIs\Models\API_Header_Model;
use App\Modules\APIs\Models\API_Parametro_Model;
use App\Modules\APIs\Models\API_Respuesta_Model;
use App\Modules\APIs\Models\API_ArquitecturaComponente_Model;
use App\Modules\APIs\Models\API_Dependencia_Model;
use App\Modules\APIs\Models\API_Observacion_Model;
use App\Modules\APIs\Models\API_Historial_Model;

use CodeIgniter\Database\BaseConnection;

class API_StorageService
{
    private API_Model $apiModel;
    private API_Header_Model $headerModel;
    private API_Parametro_Model $parametroModel;
    private API_Respuesta_Model $respuestaModel;
    private API_ArquitecturaComponente_Model $arquitecturaModel;
    private API_Dependencia_Model $dependenciaModel;
    private API_Observacion_Model $observacionModel;
    private API_Historial_Model $historialModel;

    private BaseConnection $db;

    private const ID_USUARIO_TEMPORAL = 1;


    public function __construct()
    {
        $this->apiModel =
            new API_Model();

        $this->headerModel =
            new API_Header_Model();

        $this->parametroModel =
            new API_Parametro_Model();

        $this->respuestaModel =
            new API_Respuesta_Model();

        $this->arquitecturaModel =
            new API_ArquitecturaComponente_Model();

        $this->dependenciaModel =
            new API_Dependencia_Model();

        $this->observacionModel =
            new API_Observacion_Model();

        $this->historialModel =
            new API_Historial_Model();

        $this->db =
            db_connect();
    }


    /*==================================================
    =                  OBTENER TODOS                  =
    ==================================================*/

    public function obtenerTodos(): array
    {
        $apis =
            $this->apiModel
            ->obtenerTodasCompletas();


        foreach (
            $apis
            as &$api
        ) {

            $api =
                $this->completarApi(
                    $api
                );
        }

        unset($api);


        return $apis;
    }


    /*==================================================
    =                  OBTENER POR ID                 =
    ==================================================*/

    public function obtenerPorId(
        int $idApi
    ): ?array {

        $api =
            $this->apiModel
            ->obtenerCompletaPorId(
                $idApi
            );


        if ($api === null) {
            return null;
        }


        return $this->completarApi(
            $api
        );
    }


    /*==================================================
    =              OBTENER POR PROYECTO              =
    ==================================================*/

    public function obtenerPorProyecto(
        int $idProyecto
    ): array {

        $apis =
            $this->apiModel
            ->obtenerCompletasPorProyecto(
                $idProyecto
            );


        foreach (
            $apis
            as &$api
        ) {

            $api =
                $this->completarApi(
                    $api
                );
        }

        unset($api);


        return $apis;
    }


    /*==================================================
    =                     CREAR                       =
    ==================================================*/

    public function crear(
        array $datos
    ): ?array {

        $this->db
            ->transStart();


        try {

            $datosApi =
                $this->prepararDatosApiBd(
                    $datos
                );


            $datosApi[
                'id_usuario_creador'
            ] =
                self::ID_USUARIO_TEMPORAL;

            $datosApi['activo'] =
                1;


            $idApi =
                $this->apiModel
                ->insert(
                    $datosApi,
                    true
                );


            if (!$idApi) {

                throw new \RuntimeException(
                    'No fue posible registrar la API.'
                );
            }


            $idApi =
                (int) $idApi;


            $this->guardarHeaders(
                $idApi,
                $datos['headers']
                ?? []
            );


            $this->guardarParametros(
                $idApi,
                $datos['parametros']
                ?? []
            );


            $this->guardarRespuestas(
                $idApi,
                $datos['respuestas']
                ?? []
            );


            /*
             * Arquitectura, dependencias,
             * observaciones e historial también
             * pueden venir durante una creación.
             */
            $arquitectura =
                $datos['arquitectura']
                ?? [];


            if (is_array($arquitectura)) {

                $this->guardarArquitecturaInterna(
                    $idApi,
                    $arquitectura
                );
            }


            $dependencias =
                $datos['dependencias']
                ?? [];


            if (is_array($dependencias)) {

                $this->guardarDependenciasInternas(
                    $idApi,
                    $dependencias
                );
            }


            $observaciones =
                $datos[
                    'observaciones_tecnicas'
                ]
                ?? [];


            if (is_array($observaciones)) {

                $this->guardarObservacionesInternas(
                    $idApi,
                    $observaciones
                );
            }


            $historial =
                $datos['historial']
                ?? [];


            if (is_array($historial)) {

                $this->guardarHistorialInterno(
                    $idApi,
                    $historial
                );
            }


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible completar el registro de la API.'
                );
            }


            return $this->obtenerPorId(
                $idApi
            );

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =                   ACTUALIZAR                    =
    ==================================================*/

    public function actualizar(
        int $idApi,
        array $datos
    ): ?array {

        $existente =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($existente)) {
            return null;
        }


        $this->db
            ->transStart();


        try {

            $datosApi =
                $this->prepararDatosApiBd(
                    $datos
                );


            $actualizado =
                $this->apiModel
                ->update(
                    $idApi,
                    $datosApi
                );


            if ($actualizado === false) {

                throw new \RuntimeException(
                    'No fue posible actualizar la API.'
                );
            }


            /*
             * Reemplazamos únicamente las secciones
             * que forman parte de la edición general.
             */
            $this->eliminarHeaders(
                $idApi
            );

            $this->guardarHeaders(
                $idApi,
                $datos['headers']
                ?? []
            );


            $this->eliminarParametros(
                $idApi
            );

            $this->guardarParametros(
                $idApi,
                $datos['parametros']
                ?? []
            );


            $this->eliminarRespuestas(
                $idApi
            );

            $this->guardarRespuestas(
                $idApi,
                $datos['respuestas']
                ?? []
            );


            /*
             * Arquitectura, dependencias,
             * observaciones e historial se mantienen
             * mediante sus operaciones específicas.
             */


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible completar la actualización de la API.'
                );
            }


            return $this->obtenerPorId(
                $idApi
            );

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =                  DESACTIVAR                     =
    ==================================================*/

    public function desactivar(
        int $idApi
    ): ?array {

        return $this->cambiarEstadoActivo(
            $idApi,
            false
        );
    }


    /*==================================================
    =                    ACTIVAR                      =
    ==================================================*/

    public function activar(
        int $idApi
    ): ?array {

        return $this->cambiarEstadoActivo(
            $idApi,
            true
        );
    }


    /*==================================================
    =             CAMBIAR ESTADO ACTIVO              =
    ==================================================*/

    private function cambiarEstadoActivo(
        int $idApi,
        bool $activo
    ): ?array {

        $existente =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($existente)) {
            return null;
        }


        $actualizado =
            $this->apiModel
            ->update(
                $idApi,
                [
                    'activo' =>
                        $activo
                            ? 1
                            : 0,
                ]
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idApi
        );
    }


    /*==================================================
    =                    ELIMINAR                     =
    ==================================================*/

    public function eliminar(
        int $idApi
    ): bool {

        $existente =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($existente)) {
            return false;
        }


        $this->db
            ->transStart();


        try {

            /*
             * Eliminamos primero tablas hijas.
             */
            $this->eliminarHeaders(
                $idApi
            );

            $this->eliminarParametros(
                $idApi
            );

            $this->eliminarRespuestas(
                $idApi
            );

            $this->eliminarArquitecturaInterna(
                $idApi
            );

            $this->eliminarDependenciasInternas(
                $idApi
            );

            $this->eliminarObservacionesInternas(
                $idApi
            );

            $this->eliminarHistorialInterno(
                $idApi
            );


            $eliminado =
                $this->apiModel
                ->delete(
                    $idApi
                );


            if (!$eliminado) {

                throw new \RuntimeException(
                    'No fue posible eliminar la API.'
                );
            }


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible completar la eliminación de la API.'
                );
            }


            return true;

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =             ACTUALIZAR ARQUITECTURA            =
    ==================================================*/

    public function actualizarArquitectura(
        int $idApi,
        array $arquitectura
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->db
            ->transStart();


        try {

            $this->eliminarArquitecturaInterna(
                $idApi
            );


            $this->guardarArquitecturaInterna(
                $idApi,
                $arquitectura
            );


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible guardar la arquitectura.'
                );
            }


            $apiCompleta =
                $this->obtenerPorId(
                    $idApi
                );


            return $apiCompleta[
                'arquitectura'
            ]
                ?? [
                    'modulo' =>
                        '',
                    'componentes' =>
                        [],
                ];

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =             ELIMINAR ARQUITECTURA              =
    ==================================================*/

    public function eliminarArquitectura(
        int $idApi
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->db
            ->transStart();


        try {

            $this->eliminarArquitecturaInterna(
                $idApi
            );


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible eliminar la arquitectura.'
                );
            }


            return [
                'modulo' =>
                    '',
                'componentes' =>
                    [],
            ];

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =             ACTUALIZAR DEPENDENCIAS            =
    ==================================================*/

    public function actualizarDependencias(
        int $idApi,
        array $dependencias
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->db
            ->transStart();


        try {

            $this->eliminarDependenciasInternas(
                $idApi
            );


            $this->guardarDependenciasInternas(
                $idApi,
                $dependencias
            );


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible guardar las dependencias.'
                );
            }


            $apiCompleta =
                $this->obtenerPorId(
                    $idApi
                );


            return $apiCompleta[
                'dependencias'
            ]
                ?? [];

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =              ELIMINAR DEPENDENCIAS             =
    ==================================================*/

    public function eliminarDependencias(
        int $idApi
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->eliminarDependenciasInternas(
            $idApi
        );


        return [];
    }


    /*==================================================
    =            ACTUALIZAR OBSERVACIONES            =
    ==================================================*/

    public function actualizarObservaciones(
        int $idApi,
        array $observaciones
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->db
            ->transStart();


        try {

            $this->eliminarObservacionesInternas(
                $idApi
            );


            $this->guardarObservacionesInternas(
                $idApi,
                $observaciones
            );


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible guardar las observaciones.'
                );
            }


            $apiCompleta =
                $this->obtenerPorId(
                    $idApi
                );


            return $apiCompleta[
                'observaciones_tecnicas'
            ]
                ?? [];

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =             ELIMINAR OBSERVACIONES             =
    ==================================================*/

    public function eliminarObservaciones(
        int $idApi
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->eliminarObservacionesInternas(
            $idApi
        );


        return [];
    }


    /*==================================================
    =              ACTUALIZAR HISTORIAL              =
    ==================================================*/

    public function actualizarHistorial(
        int $idApi,
        array $historial
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->db
            ->transStart();


        try {

            $this->eliminarHistorialInterno(
                $idApi
            );


            $this->guardarHistorialInterno(
                $idApi,
                $historial
            );


            $this->db
                ->transComplete();


            if (
                !$this->db
                ->transStatus()
            ) {

                throw new \RuntimeException(
                    'No fue posible guardar el historial.'
                );
            }


            $apiCompleta =
                $this->obtenerPorId(
                    $idApi
                );


            return $apiCompleta[
                'historial'
            ]
                ?? [];

        } catch (\Throwable $error) {

            $this->db
                ->transRollback();

            throw $error;
        }
    }


    /*==================================================
    =               ELIMINAR HISTORIAL               =
    ==================================================*/

    public function eliminarHistorial(
        int $idApi
    ): ?array {

        $api =
            $this->apiModel
            ->find(
                $idApi
            );


        if (!is_array($api)) {
            return null;
        }


        $this->eliminarHistorialInterno(
            $idApi
        );


        return [];
    }


    /*==================================================
    =                 COMPLETAR API                   =
    ==================================================*/

    private function completarApi(
        array $api
    ): array {

        $idApi =
            (int) (
                $api['id_api']
                ?? 0
            );


        $api['id_api'] =
            $idApi;


        $api['activo'] =
            (bool) (
                $api['activo']
                ?? false
            );


        /*
         * Alias que utiliza actualmente
         * el frontend.
         */
        $api['url'] =
            $api['url_completa']
            ?? '';


        /*==================================================
        =                    HEADERS                      =
        ==================================================*/

        $headers =
            $this->headerModel
            ->obtenerPorApi(
                $idApi
            );


        $api['headers'] =
            array_map(
                static function (
                    array $header
                ): array {

                    return [
                        'nombre' =>
                            $header['nombre']
                            ?? '',

                        'valor' =>
                            $header['valor']
                            ?? '',

                        'obligatorio' =>
                            (bool) (
                                $header[
                                    'obligatorio'
                                ]
                                ?? false
                            ),

                        'descripcion' =>
                            $header[
                                'descripcion'
                            ]
                            ?? '',
                    ];
                },
                $headers
            );


        /*==================================================
        =                   PARÁMETROS                    =
        ==================================================*/

        $parametros =
            $this->parametroModel
            ->obtenerPorApi(
                $idApi
            );


        $api['parametros'] =
            array_map(
                static function (
                    array $parametro
                ): array {

                    return [
                        'nombre' =>
                            $parametro['nombre']
                            ?? '',

                        'tipo' =>
                            $parametro['tipo']
                            ?? '',

                        'obligatorio' =>
                            (bool) (
                                $parametro[
                                    'obligatorio'
                                ]
                                ?? false
                            ),

                        'descripcion' =>
                            $parametro[
                                'descripcion'
                            ]
                            ?? '',
                    ];
                },
                $parametros
            );


        /*==================================================
        =                    EJEMPLO                      =
        ==================================================*/

        $body =
            [];


        $bodyGuardado =
            $api['body_ejemplo']
            ?? null;


        if (
            is_string(
                $bodyGuardado
            )
            &&
            trim(
                $bodyGuardado
            ) !== ''
        ) {

            $bodyDecodificado =
                json_decode(
                    $bodyGuardado,
                    true
                );


            if (
                is_array(
                    $bodyDecodificado
                )
            ) {

                $body =
                    $bodyDecodificado;
            }
        }


        $api['ejemplo'] = [
            'metodo' =>
                $api['metodo']
                ?? '',

            'endpoint' =>
                $api['endpoint']
                ?? '',

            'url' =>
                $api['url']
                ?? '',

            'body' =>
                $body,
        ];


        /*==================================================
        =                   RESPUESTAS                    =
        ==================================================*/

        $respuestas =
            $this->respuestaModel
            ->obtenerPorApi(
                $idApi
            );


        $api['respuestas'] =
            array_map(
                static function (
                    array $respuesta
                ): array {

                    $body =
                        [];


                    $bodyGuardado =
                        $respuesta['body']
                        ?? null;


                    if (
                        is_string(
                            $bodyGuardado
                        )
                        &&
                        trim(
                            $bodyGuardado
                        ) !== ''
                    ) {

                        $decodificado =
                            json_decode(
                                $bodyGuardado,
                                true
                            );


                        if (
                            is_array(
                                $decodificado
                            )
                        ) {

                            $body =
                                $decodificado;
                        }
                    }


                    return [
                        'codigo' =>
                            (int) (
                                $respuesta[
                                    'codigo_http'
                                ]
                                ?? 0
                            ),

                        'descripcion' =>
                            $respuesta[
                                'descripcion'
                            ]
                            ?? '',

                        'body' =>
                            $body,
                    ];
                },
                $respuestas
            );


        /*==================================================
        =                  ARQUITECTURA                   =
        ==================================================*/

        $componentes =
            $this->arquitecturaModel
            ->obtenerPorApi(
                $idApi
            );


        $api[
            'arquitectura'
        ] = [

            'modulo' =>
                $api[
                    'arquitectura_modulo'
                ]
                ?? '',

            'componentes' =>
                array_map(
                    static function (
                        array $componente
                    ): array {

                        return [
                            'tipo' =>
                                $componente[
                                    'tipo'
                                ]
                                ?? '',

                            'archivo' =>
                                $componente[
                                    'archivo_componente'
                                ]
                                ?? '',
                        ];
                    },
                    $componentes
                ),
        ];


        /*==================================================
        =                 DEPENDENCIAS                    =
        ==================================================*/

        $dependencias =
            $this->dependenciaModel
            ->obtenerPorApi(
                $idApi
            );


        $api[
            'dependencias'
        ] =
            array_map(
                static function (
                    array $dependencia
                ): array {

                    return [
                        'tipo' =>
                            $dependencia[
                                'tipo'
                            ]
                            ?? '',

                        'nombre' =>
                            $dependencia[
                                'nombre'
                            ]
                            ?? '',

                        'descripcion' =>
                            $dependencia[
                                'descripcion'
                            ]
                            ?? '',

                        'estado' =>
                            $dependencia[
                                'estado'
                            ]
                            ?? '',
                    ];
                },
                $dependencias
            );


        /*==================================================
        =               OBSERVACIONES                    =
        ==================================================*/

        $observaciones =
            $this->observacionModel
            ->obtenerPorApi(
                $idApi
            );


        $api[
            'observaciones_tecnicas'
        ] =
            array_map(
                function (
                    array $observacion
                ): array {

                    return [
                        'tipo' =>
                            $this->normalizarTipoObservacionFrontend(
                                (string) (
                                    $observacion[
                                        'tipo'
                                    ]
                                    ?? ''
                                )
                            ),

                        'mensaje' =>
                            $observacion[
                                'mensaje'
                            ]
                            ?? '',
                    ];
                },
                $observaciones
            );


        /*==================================================
        =                   HISTORIAL                     =
        ==================================================*/

        $historial =
            $this->historialModel
            ->obtenerPorApi(
                $idApi
            );


        $api[
            'historial'
        ] =
            array_map(
                static function (
                    array $registro
                ): array {

                    return [
                        'version' =>
                            $registro[
                                'version'
                            ]
                            ?? '',

                        'descripcion' =>
                            $registro[
                                'descripcion_cambio'
                            ]
                            ?? '',

                        'fecha' =>
                            $registro[
                                'fecha'
                            ]
                            ?? '',
                    ];
                },
                $historial
            );


        /*
         * Campos que el frontend espera como
         * cadenas aunque en BD puedan ser NULL.
         */
        $camposTexto = [
            'descripcion',
            'autenticacion',
            'ruta_local',
            'repositorio_url',
            'url_servidor',
            'responsable',
            'observaciones',
            'arquitectura_modulo',
        ];


        foreach (
            $camposTexto
            as $campo
        ) {

            $api[$campo] =
                $api[$campo]
                ?? '';
        }


        return $api;
    }


    /*==================================================
    =             PREPARAR DATOS API BD              =
    ==================================================*/

    private function prepararDatosApiBd(
        array $datos
    ): array {

        $idProyecto =
            (int) (
                $datos[
                    'id_proyecto'
                ]
                ?? 0
            );


        if ($idProyecto <= 0) {

            throw new \RuntimeException(
                'El proyecto asociado es obligatorio.'
            );
        }


        $idSistema =
            $datos[
                'id_sistema'
            ]
            ?? null;


        if (
            $idSistema === ''
            ||
            $idSistema === null
        ) {

            $idSistema =
                null;

        } else {

            $idSistema =
                (int) $idSistema;


            if ($idSistema <= 0) {
                $idSistema = null;
            }
        }


        $nombre =
            trim(
                (string) (
                    $datos['nombre']
                    ?? ''
                )
            );


        if ($nombre === '') {

            throw new \RuntimeException(
                'El nombre de la API es obligatorio.'
            );
        }


        $endpoint =
            trim(
                (string) (
                    $datos['endpoint']
                    ?? ''
                )
            );


        if ($endpoint === '') {

            throw new \RuntimeException(
                'El endpoint es obligatorio.'
            );
        }


        $bodyEjemplo =
            $datos['ejemplo']['body']
            ?? [];


        if (!is_array($bodyEjemplo)) {
            $bodyEjemplo = [];
        }


        return [

            'id_proyecto' =>
                $idProyecto,

            'id_sistema' =>
                $idSistema,

            'nombre' =>
                $nombre,

            'id_estado' =>
                $this->obtenerIdCatalogo(
                    'cat_estados',
                    'id_estado',
                    (string) (
                        $datos['estado']
                        ?? ''
                    )
                ),

            'id_metodo' =>
                $this->obtenerIdCatalogo(
                    'cat_metodos_http',
                    'id_metodo',
                    strtoupper(
                        trim(
                            (string) (
                                $datos[
                                    'metodo'
                                ]
                                ?? ''
                            )
                        )
                    )
                ),

            'descripcion' =>
                $this->normalizarNullable(
                    $datos['descripcion']
                    ?? null
                ),

            'endpoint' =>
                $endpoint,

            'url_completa' =>
                $this->normalizarNullable(
                    $datos['url']
                    ?? null
                ),

            'autenticacion' =>
                $this->normalizarNullable(
                    $datos['autenticacion']
                    ?? null
                ),

            'ruta_local' =>
                $this->normalizarNullable(
                    $datos['ruta_local']
                    ?? null
                ),

            'repositorio_url' =>
                $this->normalizarNullable(
                    $datos['repositorio_url']
                    ?? null
                ),

            'url_servidor' =>
                $this->normalizarNullable(
                    $datos['url_servidor']
                    ?? null
                ),

            'responsable' =>
                $this->normalizarNullable(
                    $datos['responsable']
                    ?? null
                ),

            'observaciones' =>
                $this->normalizarNullable(
                    $datos['observaciones']
                    ?? null
                ),

            'body_ejemplo' =>
                json_encode(
                    $bodyEjemplo,
                    JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                ),

            'arquitectura_modulo' =>
                $this->normalizarNullable(
                    $datos[
                        'arquitectura'
                    ]['modulo']
                    ?? null
                ),
        ];
    }


    /*==================================================
    =                   HEADERS                       =
    ==================================================*/

    private function guardarHeaders(
        int $idApi,
        mixed $headers
    ): void {

        if (!is_array($headers)) {
            return;
        }


        foreach (
            $headers
            as $header
        ) {

            if (!is_array($header)) {
                continue;
            }


            $nombre =
                trim(
                    (string) (
                        $header['nombre']
                        ?? ''
                    )
                );


            if ($nombre === '') {
                continue;
            }


            $this->headerModel
                ->insert([
                    'id_api' =>
                        $idApi,

                    'nombre' =>
                        $nombre,

                    'valor' =>
                        $this->normalizarNullable(
                            $header['valor']
                            ?? null
                        ),

                    'obligatorio' =>
                        !empty(
                            $header[
                                'obligatorio'
                            ]
                        )
                            ? 1
                            : 0,

                    'descripcion' =>
                        $this->normalizarNullable(
                            $header[
                                'descripcion'
                            ]
                            ?? null
                        ),
                ]);
        }
    }


    private function eliminarHeaders(
        int $idApi
    ): void {

        $this->headerModel
            ->where(
                'id_api',
                $idApi
            )
            ->delete();
    }


    /*==================================================
    =                  PARÁMETROS                     =
    ==================================================*/

    private function guardarParametros(
        int $idApi,
        mixed $parametros
    ): void {

        if (!is_array($parametros)) {
            return;
        }


        foreach (
            $parametros
            as $parametro
        ) {

            if (!is_array($parametro)) {
                continue;
            }


            $nombre =
                trim(
                    (string) (
                        $parametro['nombre']
                        ?? ''
                    )
                );


            if ($nombre === '') {
                continue;
            }


            $this->parametroModel
                ->insert([
                    'id_api' =>
                        $idApi,

                    'id_tipo_parametro' =>
                        $this->obtenerIdCatalogo(
                            'cat_tipos_parametro',
                            'id_tipo_parametro',
                            (string) (
                                $parametro[
                                    'tipo'
                                ]
                                ?? ''
                            )
                        ),

                    'nombre' =>
                        $nombre,

                    'obligatorio' =>
                        !empty(
                            $parametro[
                                'obligatorio'
                            ]
                        )
                            ? 1
                            : 0,

                    'descripcion' =>
                        $this->normalizarNullable(
                            $parametro[
                                'descripcion'
                            ]
                            ?? null
                        ),
                ]);
        }
    }


    private function eliminarParametros(
        int $idApi
    ): void {

        $this->parametroModel
            ->where(
                'id_api',
                $idApi
            )
            ->delete();
    }


    /*==================================================
    =                   RESPUESTAS                    =
    ==================================================*/

    private function guardarRespuestas(
        int $idApi,
        mixed $respuestas
    ): void {

        if (!is_array($respuestas)) {
            return;
        }


        foreach (
            $respuestas
            as $respuesta
        ) {

            if (!is_array($respuesta)) {
                continue;
            }


            $codigo =
                (int) (
                    $respuesta['codigo']
                    ?? 0
                );


            if ($codigo <= 0) {
                continue;
            }


            $body =
                $respuesta['body']
                ?? [];


            if (!is_array($body)) {
                $body = [];
            }


            $this->respuestaModel
                ->insert([
                    'id_api' =>
                        $idApi,

                    'codigo_http' =>
                        $codigo,

                    'descripcion' =>
                        $this->normalizarNullable(
                            $respuesta[
                                'descripcion'
                            ]
                            ?? null
                        ),

                    'body' =>
                        json_encode(
                            $body,
                            JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES
                        ),
                ]);
        }
    }


    private function eliminarRespuestas(
        int $idApi
    ): void {

        $this->respuestaModel
            ->where(
                'id_api',
                $idApi
            )
            ->delete();
    }


    /*==================================================
    =                 ARQUITECTURA                    =
    ==================================================*/

    private function guardarArquitecturaInterna(
        int $idApi,
        array $arquitectura
    ): void {

        $modulo =
            $this->normalizarNullable(
                $arquitectura[
                    'modulo'
                ]
                ?? null
            );


        $this->apiModel
            ->update(
                $idApi,
                [
                    'arquitectura_modulo' =>
                        $modulo,
                ]
            );


        $componentes =
            $arquitectura[
                'componentes'
            ]
            ?? [];


        if (!is_array($componentes)) {
            return;
        }


        foreach (
            $componentes
            as $componente
        ) {

            if (!is_array($componente)) {
                continue;
            }


            $tipo =
                trim(
                    (string) (
                        $componente['tipo']
                        ?? ''
                    )
                );


            $archivo =
                trim(
                    (string) (
                        $componente['archivo']
                        ?? ''
                    )
                );


            if (
                $tipo === ''
                ||
                $archivo === ''
            ) {
                continue;
            }


            $this->arquitecturaModel
                ->insert([
                    'id_api' =>
                        $idApi,

                    'id_tipo_arquitectura' =>
                        $this->obtenerIdCatalogo(
                            'cat_tipos_arquitectura',
                            'id_tipo_arquitectura',
                            $tipo
                        ),

                    'archivo_componente' =>
                        $archivo,
                ]);
        }
    }


    private function eliminarArquitecturaInterna(
        int $idApi
    ): void {

        $this->arquitecturaModel
            ->where(
                'id_api',
                $idApi
            )
            ->delete();


        $this->apiModel
            ->update(
                $idApi,
                [
                    'arquitectura_modulo' =>
                        null,
                ]
            );
    }


    /*==================================================
    =                 DEPENDENCIAS                    =
    ==================================================*/

    private function guardarDependenciasInternas(
        int $idApi,
        array $dependencias
    ): void {

        foreach (
            $dependencias
            as $dependencia
        ) {

            if (!is_array($dependencia)) {
                continue;
            }


            $nombre =
                trim(
                    (string) (
                        $dependencia[
                            'nombre'
                        ]
                        ?? ''
                    )
                );


            if ($nombre === '') {
                continue;
            }


            $this->dependenciaModel
                ->insert([
                    'id_api' =>
                        $idApi,

                    'id_tipo_dependencia' =>
                        $this->obtenerIdCatalogo(
                            'cat_tipos_dependencia',
                            'id_tipo_dependencia',
                            (string) (
                                $dependencia[
                                    'tipo'
                                ]
                                ?? ''
                            )
                        ),

                    'nombre' =>
                        $nombre,

                    'descripcion' =>
                        $this->normalizarNullable(
                            $dependencia[
                                'descripcion'
                            ]
                            ?? null
                        ),

                    'id_estado_dependencia' =>
                        $this->obtenerIdCatalogo(
                            'cat_estados_dependencia',
                            'id_estado_dependencia',
                            (string) (
                                $dependencia[
                                    'estado'
                                ]
                                ?? ''
                            )
                        ),
                ]);
        }
    }


    private function eliminarDependenciasInternas(
        int $idApi
    ): void {

        $this->dependenciaModel
            ->where(
                'id_api',
                $idApi
            )
            ->delete();
    }


    /*==================================================
    =                OBSERVACIONES                    =
    ==================================================*/

    private function guardarObservacionesInternas(
        int $idApi,
        array $observaciones
    ): void {

        foreach (
            $observaciones
            as $observacion
        ) {

            if (!is_array($observacion)) {
                continue;
            }


            $mensaje =
                trim(
                    (string) (
                        $observacion[
                            'mensaje'
                        ]
                        ?? ''
                    )
                );


            if ($mensaje === '') {
                continue;
            }


            $tipoCatalogo =
                $this->normalizarTipoObservacionCatalogo(
                    (string) (
                        $observacion[
                            'tipo'
                        ]
                        ?? ''
                    )
                );


            $this->observacionModel
                ->insert([
                    'id_api' =>
                        $idApi,

                    'id_tipo_observacion' =>
                        $this->obtenerIdCatalogo(
                            'cat_tipos_observacion_api',
                            'id_tipo_observacion',
                            $tipoCatalogo
                        ),

                    'mensaje' =>
                        $mensaje,
                ]);
        }
    }


    private function eliminarObservacionesInternas(
        int $idApi
    ): void {

        $this->observacionModel
            ->where(
                'id_api',
                $idApi
            )
            ->delete();
    }


    /*==================================================
    =                   HISTORIAL                     =
    ==================================================*/

    private function guardarHistorialInterno(
        int $idApi,
        array $historial
    ): void {

        foreach (
            $historial
            as $registro
        ) {

            if (!is_array($registro)) {
                continue;
            }


            $version =
                trim(
                    (string) (
                        $registro[
                            'version'
                        ]
                        ?? ''
                    )
                );


            if ($version === '') {
                continue;
            }


            $this->historialModel
                ->insert([
                    'id_api' =>
                        $idApi,

                    'version' =>
                        $version,

                    'descripcion_cambio' =>
                        $this->normalizarNullable(
                            $registro[
                                'descripcion'
                            ]
                            ?? null
                        ),

                    'fecha' =>
                        $this->normalizarNullable(
                            $registro[
                                'fecha'
                            ]
                            ?? null
                        ),
                ]);
        }
    }


    private function eliminarHistorialInterno(
        int $idApi
    ): void {

        $this->historialModel
            ->where(
                'id_api',
                $idApi
            )
            ->delete();
    }


    /*==================================================
    =               BUSCAR CATÁLOGO                  =
    ==================================================*/

    private function obtenerIdCatalogo(
        string $tabla,
        string $campoId,
        string $nombre
    ): int {

        $nombre =
            trim(
                $nombre
            );


        if ($nombre === '') {

            throw new \RuntimeException(
                'Falta un valor obligatorio de catálogo.'
            );
        }


        $registro =
            $this->db
            ->table(
                $tabla
            )
            ->select(
                $campoId
            )
            ->where(
                'LOWER(nombre)',
                mb_strtolower(
                    $nombre,
                    'UTF-8'
                )
            )
            ->get()
            ->getRowArray();


        if (
            !is_array(
                $registro
            )
            ||
            !isset(
                $registro[
                    $campoId
                ]
            )
        ) {

            throw new \RuntimeException(
                'El valor "'
                . $nombre
                . '" no existe en el catálogo '
                . $tabla
                . '.'
            );
        }


        return (int)
            $registro[
                $campoId
            ];
    }


    /*==================================================
    =         OBSERVACIÓN → CATÁLOGO                 =
    ==================================================*/

    private function normalizarTipoObservacionCatalogo(
        string $tipo
    ): string {

        $tipo =
            mb_strtolower(
                trim(
                    $tipo
                ),
                'UTF-8'
            );


        return match ($tipo) {

            'informacion',
            'información' =>
                'Información',

            'recomendacion',
            'recomendación' =>
                'Recomendación',

            'importante' =>
                'Importante',

            default =>
                trim(
                    $tipo
                ),
        };
    }


    /*==================================================
    =         OBSERVACIÓN → FRONTEND                 =
    ==================================================*/

    private function normalizarTipoObservacionFrontend(
        string $tipo
    ): string {

        $tipo =
            mb_strtolower(
                trim(
                    $tipo
                ),
                'UTF-8'
            );


        return match ($tipo) {

            'información',
            'informacion' =>
                'informacion',

            'recomendación',
            'recomendacion' =>
                'recomendacion',

            'importante' =>
                'importante',

            default =>
                $tipo,
        };
    }


    /*==================================================
    =                NORMALIZAR TEXTO                 =
    ==================================================*/

    private function normalizarNullable(
        mixed $valor
    ): ?string {

        $valor =
            trim(
                (string) (
                    $valor
                    ?? ''
                )
            );


        return $valor === ''
            ? null
            : $valor;
    }
}