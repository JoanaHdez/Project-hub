<?php

namespace App\Modules\Modulos\Controllers;

use App\Controllers\BaseController;

use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Modulos\Services\Modulo_StorageService;

use App\Services\Actividad_StorageService;


class Modulos_Controller extends BaseController
{
    private Sistema_StorageService $sistemaStorage;

    private Proyecto_StorageService $proyectoStorage;

    private Modulo_StorageService $moduloStorage;

    private Actividad_StorageService $actividadStorage;


    public function __construct()
    {
        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->moduloStorage =
            new Modulo_StorageService();

        $this->actividadStorage =
            new Actividad_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $modulos =
            $this->moduloStorage
            ->obtenerTodos();


        /*==================================================
        =                TOTAL DE MÓDULOS                   =
        ==================================================*/

        $totalesModulos = [];

        foreach (
            $modulos
            as $modulo
        ) {

            $idSistema =
                (int) (
                    $modulo['id_sistema']
                    ?? 0
                );


            if ($idSistema <= 0) {
                continue;
            }


            if (
                !isset(
                    $totalesModulos[
                        $idSistema
                    ]
                )
            ) {

                $totalesModulos[
                    $idSistema
                ] = 0;
            }


            $totalesModulos[
                $idSistema
            ]++;
        }


        /*==================================================
        =              PREPARAR SISTEMAS                    =
        ==================================================*/

        $sistemasVista =
            array_map(
                static function (
                    array $sistema
                ) use (
                    $totalesModulos
                ): array {

                    $idSistema =
                        (int) (
                            $sistema[
                                'id_sistema'
                            ]
                            ?? 0
                        );


                    return array_merge(
                        $sistema,
                        [
                            /*
                             * Sistema_Model ya obtiene
                             * proyecto_nombre mediante JOIN.
                             */
                            'proyecto_nombre' =>
                                $sistema[
                                    'proyecto_nombre'
                                ]
                                ?? 'Sin proyecto',

                            'total_modulos' =>
                                $totalesModulos[
                                    $idSistema
                                ]
                                ?? 0,
                        ]
                    );
                },
                $sistemas
            );


        /*==================================================
        =                    VISTA                          =
        ==================================================*/

        return view(
            'App\Modules\Modulos\Views\index',
            [
                'sistemas' =>
                    $sistemasVista,

                'modulos' =>
                    $modulos,
            ]
        );
    }


    /*==================================================
    =                    CREAR MÓDULO                   =
    ==================================================*/

    public function crear()
    {
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


        /*==================================================
        =              SISTEMA ASOCIADO                    =
        ==================================================*/

        $idSistema =
            (int) (
                $datos['id_sistema']
                ?? 0
            );


        if ($idSistema <= 0) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el sistema asociado.',
                ]);
        }


        $sistema =
            $this->sistemaStorage
            ->obtenerPorId(
                $idSistema
            );


        if ($sistema === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El sistema seleccionado no existe.',
                ]);
        }


        /*==================================================
        =              VALIDAR NOMBRE                      =
        ==================================================*/

        $nombre =
            trim(
                (string) (
                    $datos['nombre']
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
                        'El nombre del módulo es obligatorio.',
                ]);
        }


        try {

            /*==================================================
            =                  CREAR                           =
            ==================================================*/

            $modulo =
                $this->moduloStorage
                ->crear(
                    $datos
                );


            if ($modulo === null) {

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No fue posible registrar el módulo.',
                    ]);
            }


            /*==================================================
            =              TOTAL ACTUALIZADO                   =
            ==================================================*/

            $totalModulos =
                count(
                    $this->moduloStorage
                    ->obtenerPorSistema(
                        $idSistema
                    )
                );


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                'Agregó',
                (int) (
                    $modulo[
                        'id_modulo'
                    ]
                    ?? 0
                ),
                'Agregó el módulo "'
                    . (
                        $modulo[
                            'nombre'
                        ]
                        ?? 'Módulo'
                    )
                    . '" al sistema "'
                    . (
                        $sistema[
                            'nombre'
                        ]
                        ?? 'Sistema'
                    )
                    . '".'
            );


            /*==================================================
            =                  RESPUESTA                       =
            ==================================================*/

            return $this->response
                ->setStatusCode(201)
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Módulo registrado correctamente.',

                    'modulo' =>
                        $modulo,

                    'total_modulos' =>
                        $totalModulos,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al registrar módulo: {mensaje}',
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
                        ?: 'Ocurrió un error al registrar el módulo.',
                ]);
        }
    }


    /*==================================================
    =                  ACTUALIZAR MÓDULO               =
    ==================================================*/

    public function actualizar(
        int $idModulo
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


        /*==================================================
        =               MÓDULO EXISTENTE                    =
        ==================================================*/

        $moduloExistente =
            $this->moduloStorage
            ->obtenerPorId(
                $idModulo
            );


        if ($moduloExistente === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el módulo solicitado.',
                ]);
        }


        /*==================================================
        =              SISTEMA ASOCIADO                    =
        ==================================================*/

        $idSistema =
            (int) (
                $datos['id_sistema']
                ?? (
                    $moduloExistente[
                        'id_sistema'
                    ]
                    ?? 0
                )
            );


        if ($idSistema <= 0) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el sistema asociado.',
                ]);
        }


        $sistema =
            $this->sistemaStorage
            ->obtenerPorId(
                $idSistema
            );


        if ($sistema === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El sistema asociado no existe.',
                ]);
        }


        $datos['id_sistema'] =
            $idSistema;


        /*==================================================
        =              VALIDAR NOMBRE                      =
        ==================================================*/

        $nombre =
            trim(
                (string) (
                    $datos['nombre']
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
                        'El nombre del módulo es obligatorio.',
                ]);
        }


        try {

            /*==================================================
            =                  ACTUALIZAR                      =
            ==================================================*/

            $moduloActualizado =
                $this->moduloStorage
                ->actualizar(
                    $idModulo,
                    $datos
                );


            if ($moduloActualizado === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró el módulo solicitado.',
                    ]);
            }


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                'Editó',
                $idModulo,
                'Editó el módulo "'
                    . (
                        $moduloActualizado[
                            'nombre'
                        ]
                        ?? 'Módulo'
                    )
                    . '" del sistema "'
                    . (
                        $sistema[
                            'nombre'
                        ]
                        ?? 'Sistema'
                    )
                    . '".'
            );


            /*==================================================
            =                  RESPUESTA                       =
            ==================================================*/

            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Módulo actualizado correctamente.',

                    'modulo' =>
                        $moduloActualizado,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar módulo {id}: {mensaje}',
                [
                    'id' =>
                        $idModulo,

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
                        ?: 'Ocurrió un error al actualizar el módulo.',
                ]);
        }
    }


    /*==================================================
    =              ACTUALIZAR IMAGEN                   =
    ==================================================*/

    public function actualizarImagen(
        int $idModulo
    ) {

        $modulo =
            $this->moduloStorage
            ->obtenerPorId(
                $idModulo
            );


        if ($modulo === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el módulo solicitado.',
                ]);
        }


        /*==================================================
        =              OBTENER ARCHIVO                    =
        ==================================================*/

        $archivo =
            $this->request
            ->getFile(
                'imagen'
            );


        if (
            !$archivo
            ||
            !$archivo->isValid()
            ||
            $archivo->hasMoved()
        ) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se recibió una imagen válida.',
                ]);
        }


        /*==================================================
        =              VALIDAR FORMATO                    =
        ==================================================*/

        $tiposPermitidos = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];


        $mime =
            $archivo->getMimeType();


        if (
            !in_array(
                $mime,
                $tiposPermitidos,
                true
            )
        ) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'La imagen debe ser JPG, PNG o WebP.',
                ]);
        }


        /*==================================================
        =              VALIDAR TAMAÑO                     =
        ==================================================*/

        $tamanoMaximo =
            5 * 1024 * 1024;


        if (
            $archivo->getSize()
            >
            $tamanoMaximo
        ) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'La imagen no puede superar los 5 MB.',
                ]);
        }


        /*==================================================
        =              DIRECTORIO DESTINO                 =
        ==================================================*/

        $directorio =
            FCPATH
            . 'uploads/modulos';


        if (!is_dir($directorio)) {

            mkdir(
                $directorio,
                0775,
                true
            );
        }


        /*==================================================
        =              NOMBRE DEL ARCHIVO                 =
        ==================================================*/

        $extension =
            strtolower(
                $archivo->getExtension()
            );


        $nombreArchivo =
            'modulo-'
            . $idModulo
            . '-'
            . time()
            . '.'
            . $extension;


        try {

            /*==================================================
            =              GUARDAR ARCHIVO                    =
            ==================================================*/

            $archivo->move(
                $directorio,
                $nombreArchivo
            );


            /*==================================================
            =              RUTA PÚBLICA                       =
            ==================================================*/

            $rutaImagen =
                base_url(
                    'uploads/modulos/'
                    . $nombreArchivo
                );


            /*==================================================
            =              ACTUALIZAR BD                      =
            ==================================================*/

            $moduloActualizado =
                $this->moduloStorage
                ->actualizarImagen(
                    $idModulo,
                    $rutaImagen
                );


            if ($moduloActualizado === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No fue posible actualizar la imagen del módulo.',
                    ]);
            }


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                'Editó',
                $idModulo,
                'Actualizó la imagen del módulo "'
                    . (
                        $moduloActualizado[
                            'nombre'
                        ]
                        ?? 'Módulo'
                    )
                    . '".'
            );


            /*==================================================
            =                  RESPUESTA                       =
            ==================================================*/

            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Imagen actualizada correctamente.',

                    'imagen' =>
                        $rutaImagen,

                    'modulo' =>
                        $moduloActualizado,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar imagen del módulo {id}: {mensaje}',
                [
                    'id' =>
                        $idModulo,

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
                        'No fue posible actualizar la imagen del módulo.',
                ]);
        }
    }


    /*==================================================
    =                  ELIMINAR MÓDULO                 =
    ==================================================*/

    public function eliminar(
        int $idModulo
    ) {

        /*==================================================
        =              OBTENER MÓDULO                     =
        ==================================================*/

        $modulo =
            $this->moduloStorage
            ->obtenerPorId(
                $idModulo
            );


        if ($modulo === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el módulo solicitado.',
                ]);
        }


        $idSistema =
            (int) (
                $modulo[
                    'id_sistema'
                ]
                ?? 0
            );


        $sistema =
            $idSistema > 0
                ? $this->sistemaStorage
                    ->obtenerPorId(
                        $idSistema
                    )
                : null;


        try {

            /*==================================================
            =                    ELIMINAR                      =
            ==================================================*/

            $eliminado =
                $this->moduloStorage
                ->eliminar(
                    $idModulo
                );


            if (!$eliminado) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró el módulo solicitado.',
                    ]);
            }


            /*==================================================
            =             TOTAL ACTUALIZADO                   =
            ==================================================*/

            $totalModulos =
                $idSistema > 0
                    ? count(
                        $this->moduloStorage
                        ->obtenerPorSistema(
                            $idSistema
                        )
                    )
                    : 0;


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                'Eliminó',
                $idModulo,
                'Eliminó el módulo "'
                    . (
                        $modulo[
                            'nombre'
                        ]
                        ?? 'Módulo'
                    )
                    . '" del sistema "'
                    . (
                        $sistema[
                            'nombre'
                        ]
                        ?? 'Sistema'
                    )
                    . '".'
            );


            /*==================================================
            =                  RESPUESTA                       =
            ==================================================*/

            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Módulo eliminado correctamente.',

                    'id_modulo' =>
                        $idModulo,

                    'id_sistema' =>
                        $idSistema,

                    'total_modulos' =>
                        $totalModulos,
                ]);

        } catch (\Throwable $error) {

            /*
             * Cuando existan registros dependientes,
             * las FK de MySQL protegerán el módulo.
             */
            log_message(
                'error',
                'No fue posible eliminar módulo {id}: {mensaje}',
                [
                    'id' =>
                        $idModulo,

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
                        'No se puede eliminar el módulo porque existen registros asociados.',
                ]);
        }
    }


    /*==================================================
    =              CAMBIAR ESTADO MÓDULO               =
    ==================================================*/

    public function cambiarEstado(
        int $idModulo
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


        if (
            !array_key_exists(
                'activo',
                $datos
            )
        ) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se recibió el estado del módulo.',
                ]);
        }


        $activo =
            filter_var(
                $datos['activo'],
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );


        if ($activo === null) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El estado enviado no es válido.',
                ]);
        }


        try {

            /*==================================================
            =              ACTUALIZAR ESTADO                  =
            ==================================================*/

            $modulo =
                $this->moduloStorage
                ->cambiarEstado(
                    $idModulo,
                    $activo
                );


            if ($modulo === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró el módulo solicitado.',
                    ]);
            }


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                $activo
                    ? 'Activó'
                    : 'Desactivó',
                $idModulo,
                (
                    $activo
                        ? 'Activó el módulo "'
                        : 'Desactivó el módulo "'
                )
                    . (
                        $modulo[
                            'nombre'
                        ]
                        ?? 'Módulo'
                    )
                    . '".'
            );


            /*==================================================
            =                  RESPUESTA                       =
            ==================================================*/

            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        $activo
                            ? 'Módulo activado correctamente.'
                            : 'Módulo desactivado correctamente.',

                    'id_modulo' =>
                        $idModulo,

                    'activo' =>
                        $activo,

                    'modulo' =>
                        $modulo,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al cambiar estado del módulo {id}: {mensaje}',
                [
                    'id' =>
                        $idModulo,

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
                        'No fue posible actualizar el estado del módulo.',
                ]);
        }
    }


    /*==================================================
    =              REGISTRAR ACTIVIDAD                 =
    ==================================================*/

    private function registrarActividad(
        string $accion,
        int $idModulo,
        string $detalle
    ): void {

        try {

            /*
             * Actividad todavía trabaja con su
             * StorageService actual.
             *
             * Se migrará posteriormente.
             */
            $this->actividadStorage
                ->registrar([
                    'bloque' =>
                        'Módulos',

                    'accion' =>
                        $accion,

                    'entidad_tipo' =>
                        'Módulo',

                    'entidad_id' =>
                        $idModulo,

                    'detalle' =>
                        $detalle,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'No fue posible registrar actividad del módulo {id}: {mensaje}',
                [
                    'id' =>
                        $idModulo,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );
        }
    }
}