<?php

namespace App\Modules\APIs\Controllers;

use App\Controllers\BaseController;
use App\Modules\APIs\Services\API_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Services\Actividad_StorageService;

class APIs_Controller extends BaseController
{
    private API_StorageService $storage;

    private Proyecto_StorageService $proyectoStorage;

    private Actividad_StorageService $actividadStorage;


    public function __construct()
    {
        $this->storage =
            new API_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->actividadStorage =
            new Actividad_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $apisAlmacenadas =
            $this->storage
            ->obtenerTodos();


        usort(
            $apisAlmacenadas,
            static function (
                array $a,
                array $b
            ): int {

                return (int) (
                    $b['id_api']
                    ?? 0
                )
                <=>
                (int) (
                    $a['id_api']
                    ?? 0
                );
            }
        );


        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();


        /*==================================================
        =                 PREPARAR APIs                    =
        ==================================================*/

        $apis =
            array_map(
                function (
                    array $api
                ): array {

                    return $this->construirApiVista(
                        $api
                    );
                },
                $apisAlmacenadas
            );


        /*==================================================
        =           PROYECTOS DISPONIBLES                 =
        ==================================================*/

        $proyectosDisponibles =
            array_map(
                static function (
                    array $proyecto
                ): array {

                    return [

                        'id_proyecto' =>
                            $proyecto[
                                'id_proyecto'
                            ]
                            ?? null,

                        'nombre' =>
                            $proyecto[
                                'nombre'
                            ]
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


        /*==================================================
        =              VALIDAR PROYECTO                   =
        ==================================================*/

        $idProyecto =
            (int) (
                $datos[
                    'id_proyecto'
                ]
                ?? 0
            );


        $proyecto =
            $this->proyectoStorage
            ->obtenerPorId(
                $idProyecto
            );


        if ($proyecto === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El proyecto seleccionado no existe.',
                ]);
        }


        try {

            /*==================================================
            =                     CREAR                        =
            ==================================================*/

            $api =
                $this->storage
                ->crear(
                    $datos
                );


            if ($api === null) {

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No fue posible registrar la API.',
                    ]);
            }


            /*==================================================
            =              REGISTRAR ACTIVIDAD                =
            ==================================================*/

            $this->registrarActividad(
                'Agregó',
                (int) (
                    $api[
                        'id_api'
                    ]
                    ?? 0
                ),
                'Agregó la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            /*==================================================
            =                  PREPARAR VISTA                  =
            ==================================================*/

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
                    'ok' =>
                        true,

                    'mensaje' =>
                        'API registrada correctamente.',

                    'api' =>
                        $apiVista,

                    'selector_html' =>
                        $selectorHtml,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al registrar API: {mensaje}',
                [
                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'Ocurrió un error al registrar la API.',
                ]);
        }
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


        /*==================================================
        =                 VALIDAR API                     =
        ==================================================*/

        $apiExistente =
            $this->storage
            ->obtenerPorId(
                $idApi
            );


        if ($apiExistente === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la API solicitada.',
                ]);
        }


        /*==================================================
        =              VALIDAR PROYECTO                   =
        ==================================================*/

        $idProyecto =
            (int) (
                $datos[
                    'id_proyecto'
                ]
                ?? 0
            );


        $proyecto =
            $this->proyectoStorage
            ->obtenerPorId(
                $idProyecto
            );


        if ($proyecto === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El proyecto seleccionado no existe.',
                ]);
        }


        try {

            /*==================================================
            =                  ACTUALIZAR                      =
            ==================================================*/

            $apiActualizada =
                $this->storage
                ->actualizar(
                    $idApi,
                    $datos
                );


            if ($apiActualizada === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            /*==================================================
            =              REGISTRAR ACTIVIDAD                =
            ==================================================*/

            $this->registrarActividad(
                'Editó',
                $idApi,
                'Editó la API "'
                    . (
                        $apiActualizada[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            /*==================================================
            =                 PREPARAR VISTA                   =
            ==================================================*/

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
                    'ok' =>
                        true,

                    'mensaje' =>
                        'API actualizada correctamente.',

                    'api' =>
                        $apiVista,

                    'selector_html' =>
                        $selectorHtml,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'Ocurrió un error al actualizar la API.',
                ]);
        }
    }


    /*==================================================
    =             ACTUALIZAR ARQUITECTURA             =
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
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }


        $arquitectura =
            $datos[
                'arquitectura'
            ]
            ?? null;


        if (!is_array($arquitectura)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'La arquitectura enviada no es válida.',
                ]);
        }


        /*==================================================
        =              NORMALIZAR DATOS                   =
        ==================================================*/

        $arquitecturaNormalizada = [

            'modulo' =>
                trim(
                    (string) (
                        $arquitectura[
                            'modulo'
                        ]
                        ?? ''
                    )
                ),

            'componentes' =>
                is_array(
                    $arquitectura[
                        'componentes'
                    ]
                    ?? null
                )
                    ? $arquitectura[
                        'componentes'
                    ]
                    : [],
        ];


        try {

            $resultado =
                $this->storage
                ->actualizarArquitectura(
                    $idApi,
                    $arquitecturaNormalizada
                );


            if ($resultado === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $api =
                $this->storage
                ->obtenerPorId(
                    $idApi
                );


            $this->registrarActividad(
                'Editó arquitectura',
                $idApi,
                'Actualizó la arquitectura de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Arquitectura guardada correctamente.',

                    'arquitectura' =>
                        $resultado,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar arquitectura de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'No fue posible guardar la arquitectura.',
                ]);
        }
    }


    /*==================================================
    =             ACTUALIZAR DEPENDENCIAS             =
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
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }


        $dependencias =
            $datos[
                'dependencias'
            ]
            ?? null;


        if (!is_array($dependencias)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Las dependencias enviadas no son válidas.',
                ]);
        }


        /*==================================================
        =              NORMALIZAR DATOS                   =
        ==================================================*/

        $dependenciasNormalizadas = [];


        foreach (
            $dependencias
            as $dependencia
        ) {

            if (!is_array($dependencia)) {
                continue;
            }


            $tipo =
                trim(
                    (string) (
                        $dependencia[
                            'tipo'
                        ]
                        ?? ''
                    )
                );


            $nombre =
                trim(
                    (string) (
                        $dependencia[
                            'nombre'
                        ]
                        ?? ''
                    )
                );


            $descripcion =
                trim(
                    (string) (
                        $dependencia[
                            'descripcion'
                        ]
                        ?? ''
                    )
                );


            $estado =
                trim(
                    (string) (
                        $dependencia[
                            'estado'
                        ]
                        ?? ''
                    )
                );


            if (
                $tipo === ''
                &&
                $nombre === ''
                &&
                $descripcion === ''
                &&
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


        try {

            $resultado =
                $this->storage
                ->actualizarDependencias(
                    $idApi,
                    $dependenciasNormalizadas
                );


            if ($resultado === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $api =
                $this->storage
                ->obtenerPorId(
                    $idApi
                );


            $this->registrarActividad(
                'Editó dependencias',
                $idApi,
                'Actualizó las dependencias de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Dependencias guardadas correctamente.',

                    'dependencias' =>
                        $resultado,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar dependencias de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'No fue posible guardar las dependencias.',
                ]);
        }
    }


    /*==================================================
    =               ACTUALIZAR HISTORIAL              =
    ==================================================*/

    public function actualizarHistorial(
        int $idApi
    ) {

        $datos =
            $this->request
            ->getJSON(true);


        if (!is_array($datos)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }


        $historial =
            $datos[
                'historial'
            ]
            ?? null;


        if (!is_array($historial)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El historial enviado no es válido.',
                ]);
        }


        /*==================================================
        =              NORMALIZAR DATOS                   =
        ==================================================*/

        $historialNormalizado = [];


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


            $descripcion =
                trim(
                    (string) (
                        $registro[
                            'descripcion'
                        ]
                        ?? ''
                    )
                );


            $fecha =
                trim(
                    (string) (
                        $registro[
                            'fecha'
                        ]
                        ?? ''
                    )
                );


            if (
                $version === ''
                &&
                $descripcion === ''
                &&
                $fecha === ''
            ) {
                continue;
            }


            $historialNormalizado[] = [

                'version' =>
                    $version,

                'descripcion' =>
                    $descripcion,

                'fecha' =>
                    $fecha,
            ];
        }


        try {

            $resultado =
                $this->storage
                ->actualizarHistorial(
                    $idApi,
                    $historialNormalizado
                );


            if ($resultado === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $api =
                $this->storage
                ->obtenerPorId(
                    $idApi
                );


            $this->registrarActividad(
                'Editó historial',
                $idApi,
                'Actualizó el historial de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Historial guardado correctamente.',

                    'historial' =>
                        $resultado,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar historial de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'No fue posible guardar el historial.',
                ]);
        }
    }


    /*==================================================
    =             ACTUALIZAR OBSERVACIONES            =
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
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }


        $observaciones =
            $datos[
                'observaciones'
            ]
            ?? null;


        if (!is_array($observaciones)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Las observaciones enviadas no son válidas.',
                ]);
        }


        /*==================================================
        =              NORMALIZAR DATOS                   =
        ==================================================*/

        $observacionesNormalizadas = [];


        foreach (
            $observaciones
            as $observacion
        ) {

            if (!is_array($observacion)) {
                continue;
            }


            $tipo =
                trim(
                    (string) (
                        $observacion[
                            'tipo'
                        ]
                        ?? ''
                    )
                );


            $mensaje =
                trim(
                    (string) (
                        $observacion[
                            'mensaje'
                        ]
                        ?? ''
                    )
                );


            if (
                $tipo === ''
                &&
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


        try {

            $resultado =
                $this->storage
                ->actualizarObservaciones(
                    $idApi,
                    $observacionesNormalizadas
                );


            if ($resultado === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $api =
                $this->storage
                ->obtenerPorId(
                    $idApi
                );


            $this->registrarActividad(
                'Editó observaciones',
                $idApi,
                'Actualizó las observaciones técnicas de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Observaciones guardadas correctamente.',

                    'observaciones' =>
                        $resultado,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar observaciones de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'No fue posible guardar las observaciones.',
                ]);
        }
    }


    /*==================================================
    =                  DESACTIVAR API                  =
    ==================================================*/

    public function desactivar(
        int $idApi
    ) {

        try {

            $api =
                $this->storage
                ->desactivar(
                    $idApi
                );


            if ($api === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $this->registrarActividad(
                'Desactivó',
                $idApi,
                'Desactivó la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
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
                    'ok' =>
                        true,

                    'mensaje' =>
                        'API desactivada correctamente.',

                    'api' =>
                        $apiVista,

                    'selector_html' =>
                        $selectorHtml,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al desactivar API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible desactivar la API.',
                ]);
        }
    }


    /*==================================================
    =                    ACTIVAR API                   =
    ==================================================*/

    public function activar(
        int $idApi
    ) {

        try {

            $api =
                $this->storage
                ->activar(
                    $idApi
                );


            if ($api === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $this->registrarActividad(
                'Activó',
                $idApi,
                'Activó la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
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
                    'ok' =>
                        true,

                    'mensaje' =>
                        'API activada correctamente.',

                    'api' =>
                        $apiVista,

                    'selector_html' =>
                        $selectorHtml,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al activar API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible activar la API.',
                ]);
        }
    }


    /*==================================================
    =                    ELIMINAR API                  =
    ==================================================*/

    public function eliminar(
        int $idApi
    ) {

        $api =
            $this->storage
            ->obtenerPorId(
                $idApi
            );


        if ($api === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la API solicitada.',
                ]);
        }


        try {

            $eliminada =
                $this->storage
                ->eliminar(
                    $idApi
                );


            if (!$eliminada) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $this->registrarActividad(
                'Eliminó',
                $idApi,
                'Eliminó la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            $totalApis =
                count(
                    $this->storage
                    ->obtenerTodos()
                );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'API eliminada correctamente.',

                    'id_api' =>
                        $idApi,

                    'total_apis' =>
                        $totalApis,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible eliminar la API porque existen registros relacionados.',
                ]);
        }
    }


    /*==================================================
    =             ELIMINAR ARQUITECTURA               =
    ==================================================*/

    public function eliminarArquitectura(
        int $idApi
    ) {

        $api =
            $this->storage
            ->obtenerPorId(
                $idApi
            );


        if ($api === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la API solicitada.',
                ]);
        }


        try {

            $arquitectura =
                $this->storage
                ->eliminarArquitectura(
                    $idApi
                );


            if ($arquitectura === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $this->registrarActividad(
                'Eliminó arquitectura',
                $idApi,
                'Eliminó la arquitectura de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Arquitectura eliminada correctamente.',

                    'arquitectura' =>
                        $arquitectura,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar arquitectura de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible eliminar la arquitectura.',
                ]);
        }
    }


    /*==================================================
    =             ELIMINAR DEPENDENCIAS               =
    ==================================================*/

    public function eliminarDependencias(
        int $idApi
    ) {

        $api =
            $this->storage
            ->obtenerPorId(
                $idApi
            );


        if ($api === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la API solicitada.',
                ]);
        }


        try {

            $dependencias =
                $this->storage
                ->eliminarDependencias(
                    $idApi
                );


            if ($dependencias === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $this->registrarActividad(
                'Eliminó dependencias',
                $idApi,
                'Eliminó las dependencias de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Dependencias eliminadas correctamente.',

                    'dependencias' =>
                        $dependencias,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar dependencias de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible eliminar las dependencias.',
                ]);
        }
    }


    /*==================================================
    =             ELIMINAR OBSERVACIONES              =
    ==================================================*/

    public function eliminarObservaciones(
        int $idApi
    ) {

        $api =
            $this->storage
            ->obtenerPorId(
                $idApi
            );


        if ($api === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la API solicitada.',
                ]);
        }


        try {

            $observaciones =
                $this->storage
                ->eliminarObservaciones(
                    $idApi
                );


            if ($observaciones === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $this->registrarActividad(
                'Eliminó observaciones',
                $idApi,
                'Eliminó las observaciones técnicas de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Observaciones eliminadas correctamente.',

                    'observaciones' =>
                        $observaciones,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar observaciones de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible eliminar las observaciones.',
                ]);
        }
    }


    /*==================================================
    =               ELIMINAR HISTORIAL                =
    ==================================================*/

    public function eliminarHistorial(
        int $idApi
    ) {

        $api =
            $this->storage
            ->obtenerPorId(
                $idApi
            );


        if ($api === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la API solicitada.',
                ]);
        }


        try {

            $historial =
                $this->storage
                ->eliminarHistorial(
                    $idApi
                );


            if ($historial === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la API solicitada.',
                    ]);
            }


            $this->registrarActividad(
                'Eliminó historial',
                $idApi,
                'Eliminó el historial de la API "'
                    . (
                        $api[
                            'nombre'
                        ]
                        ?? 'API'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Historial eliminado correctamente.',

                    'historial' =>
                        $historial,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar historial de API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible eliminar el historial.',
                ]);
        }
    }


    /*==================================================
    =                VALIDAR DATOS                     =
    ==================================================*/

    private function validarDatosApi(
        mixed $datos
    ) {

        if (!is_array($datos)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }


        $idProyecto =
            (int) (
                $datos[
                    'id_proyecto'
                ]
                ?? 0
            );


        if ($idProyecto <= 0) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Debes seleccionar un proyecto.',
                ]);
        }


        $nombre =
            trim(
                (string) (
                    $datos[
                        'nombre'
                    ]
                    ?? ''
                )
            );


        if ($nombre === '') {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El nombre de la API es obligatorio.',
                ]);
        }


        $estado =
            trim(
                (string) (
                    $datos[
                        'estado'
                    ]
                    ?? ''
                )
            );


        if ($estado === '') {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El estado es obligatorio.',
                ]);
        }


        $metodo =
            trim(
                (string) (
                    $datos[
                        'metodo'
                    ]
                    ?? ''
                )
            );


        if ($metodo === '') {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El método HTTP es obligatorio.',
                ]);
        }


        $endpoint =
            trim(
                (string) (
                    $datos[
                        'endpoint'
                    ]
                    ?? ''
                )
            );


        if ($endpoint === '') {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El endpoint es obligatorio.',
                ]);
        }


        return null;
    }


    /*==================================================
    =               CONSTRUIR API VISTA               =
    ==================================================*/

    private function construirApiVista(
        array $api
    ): array {

        $nombreProyecto =
            $api[
                'proyecto_nombre'
            ]
            ?? '';


        /*
         * Por compatibilidad, si por alguna razón
         * el JOIN no devuelve el nombre, intentamos
         * resolverlo desde Proyecto_StorageService.
         */
        if (
            trim(
                (string)
                $nombreProyecto
            ) === ''
        ) {

            $idProyecto =
                (int) (
                    $api[
                        'id_proyecto'
                    ]
                    ?? 0
                );


            $proyecto =
                $idProyecto > 0
                    ? $this->proyectoStorage
                        ->obtenerPorId(
                            $idProyecto
                        )
                    : null;


            $nombreProyecto =
                $proyecto[
                    'nombre'
                ]
                ?? 'Sin proyecto';
        }


        return array_merge(
            $api,
            [
                'id' =>
                    $api[
                        'id_api'
                    ]
                    ?? null,

                'proyecto' =>
                    $nombreProyecto,

                'repositorio' =>
                    $api[
                        'repositorio_url'
                    ]
                    ?? '',

                'servidor' =>
                    $api[
                        'url_servidor'
                    ]
                    ?? '',
            ]
        );
    }


    /*==================================================
    =             CONSTRUIR SELECTOR HTML             =
    ==================================================*/

    private function construirSelectorHtml(
        array $api
    ): string {

        return view(
            'App\Modules\APIs\Views\components\api_selector',
            [
                'titulo' =>
                    $api[
                        'nombre'
                    ],

                'proyecto' =>
                    $api[
                        'proyecto'
                    ],

                'estado' =>
                    $api[
                        'estado'
                    ],

                'metodo' =>
                    $api[
                        'metodo'
                    ],

                'atributos' => [

                    'data-api-id' =>
                        $api[
                            'id'
                        ],

                    'data-api-activo' =>
                        !empty(
                            $api[
                                'activo'
                            ]
                        )
                            ? '1'
                            : '0',

                    'data-api-id-proyecto' =>
                        $api[
                            'id_proyecto'
                        ]
                        ?? '',

                    'data-api-id-sistema' =>
                        $api[
                            'id_sistema'
                        ]
                        ?? '',

                    'data-api-nombre' =>
                        $api[
                            'nombre'
                        ],

                    'data-api-proyecto' =>
                        $api[
                            'proyecto'
                        ],

                    'data-api-descripcion' =>
                        $api[
                            'descripcion'
                        ]
                        ?? '',

                    'data-api-estado' =>
                        $api[
                            'estado'
                        ]
                        ?? '',

                    'data-api-metodo' =>
                        $api[
                            'metodo'
                        ]
                        ?? '',

                    'data-api-endpoint' =>
                        $api[
                            'endpoint'
                        ]
                        ?? '',

                    'data-api-url' =>
                        $api[
                            'url'
                        ]
                        ?? '',

                    'data-api-autenticacion' =>
                        $api[
                            'autenticacion'
                        ]
                        ?? '',

                    'data-api-repositorio' =>
                        $api[
                            'repositorio'
                        ]
                        ?? '',

                    'data-api-ruta' =>
                        $api[
                            'ruta_local'
                        ]
                        ?? '',

                    'data-api-servidor' =>
                        $api[
                            'servidor'
                        ]
                        ?? '',

                    'data-api-headers' =>
                        json_encode(
                            $api[
                                'headers'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
                        ),

                    'data-api-parametros' =>
                        json_encode(
                            $api[
                                'parametros'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
                        ),

                    'data-api-ejemplo' =>
                        json_encode(
                            $api[
                                'ejemplo'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
                        ),

                    'data-api-respuestas' =>
                        json_encode(
                            $api[
                                'respuestas'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
                        ),

                    'data-api-arquitectura' =>
                        json_encode(
                            $api[
                                'arquitectura'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
                        ),

                    'data-api-dependencias' =>
                        json_encode(
                            $api[
                                'dependencias'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
                        ),

                    'data-api-observaciones' =>
                        json_encode(
                            $api[
                                'observaciones_tecnicas'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
                        ),

                    'data-api-historial' =>
                        json_encode(
                            $api[
                                'historial'
                            ]
                            ?? [],
                            JSON_UNESCAPED_UNICODE
                            |
                            JSON_UNESCAPED_SLASHES
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
    =              REGISTRAR ACTIVIDAD                =
    ==================================================*/

    private function registrarActividad(
        string $accion,
        int $idApi,
        string $detalle
    ): void {

        try {

            $this->actividadStorage
                ->registrar([
                    'bloque' =>
                        'APIs',

                    'accion' =>
                        $accion,

                    'entidad_tipo' =>
                        'API',

                    'entidad_id' =>
                        $idApi,

                    'detalle' =>
                        $detalle,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'No fue posible registrar actividad de la API {id}: {mensaje}',
                [
                    'id' =>
                        $idApi,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );
        }
    }
}