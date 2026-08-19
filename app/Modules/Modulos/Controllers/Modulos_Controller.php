<?php

namespace App\Modules\Modulos\Controllers;

use App\Controllers\BaseController;

use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Modulos\Services\Modulo_StorageService;

class Modulos_Controller extends BaseController
{
    private Sistema_StorageService $sistemaStorage;
    private Proyecto_StorageService $proyectoStorage;
    private Modulo_StorageService $moduloStorage;


    public function __construct()
    {
        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->moduloStorage =
            new Modulo_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();

        $modulos =
            $this->moduloStorage
            ->obtenerTodos();


        /*==================================================
        =              NOMBRES DE PROYECTOS                =
        ==================================================*/

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {
            $idProyecto =
                (int) (
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


        /*==================================================
        =                TOTAL DE MÓDULOS                   =
        ==================================================*/

        $totalesModulos = [];

        foreach ($modulos as $modulo) {
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
                    $totalesModulos[$idSistema]
                )
            ) {
                $totalesModulos[$idSistema] = 0;
            }

            $totalesModulos[$idSistema]++;
        }


        /*==================================================
        =              PREPARAR SISTEMAS                    =
        ==================================================*/

        $sistemasVista =
            array_map(
                static function (
                    array $sistema
                ) use (
                    $nombresProyectos,
                    $totalesModulos
                ): array {
                    $idProyecto =
                        (int) (
                            $sistema['id_proyecto']
                            ?? 0
                        );

                    $idSistema =
                        (int) (
                            $sistema['id_sistema']
                            ?? 0
                        );

                    return array_merge(
                        $sistema,
                        [
                            'proyecto_nombre' =>
                            $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto',

                            'total_modulos' =>
                            $totalesModulos[$idSistema]
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
                    'ok' => false,
                    'mensaje' =>
                    'Los datos enviados no son válidos.',
                ]);
        }

        $idSistema =
            (int) (
                $datos['id_sistema']
                ?? 0
            );

        if ($idSistema <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,
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
                    'ok' => false,
                    'mensaje' =>
                    'El sistema seleccionado no existe.',
                ]);
        }

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
                    'ok' => false,
                    'mensaje' =>
                    'El nombre del módulo es obligatorio.',
                ]);
        }

        $tipo =
            trim(
                (string) (
                    $datos['tipo']
                    ?? ''
                )
            );

        $descripcion =
            trim(
                (string) (
                    $datos['descripcion']
                    ?? ''
                )
            );

        $url =
            trim(
                (string) (
                    $datos['url']
                    ?? ''
                )
            );

        $modulos =
            $this->moduloStorage
            ->obtenerTodos();

        $modulo = [
            'id_modulo' =>
            $this->moduloStorage
                ->generarNuevoId(
                    $modulos
                ),

            'id_sistema' =>
            $idSistema,

            'nombre' =>
            $nombre,

            'tipo' =>
            $tipo,

            'descripcion' =>
            $descripcion,

            'url' =>
            $url,

            'activo' =>
            true,
        ];

        $modulos[] =
            $modulo;

        $this->moduloStorage
            ->guardarTodos(
                $modulos
            );

        $totalModulos =
            count(
                $this->moduloStorage
                    ->obtenerPorSistema(
                        $idSistema
                    )
            );

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Módulo registrado correctamente.',

                'modulo' =>
                $modulo,

                'total_modulos' =>
                $totalModulos,
            ]);
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
                    'ok' => false,

                    'mensaje' =>
                    'Los datos enviados no son válidos.',
                ]);
        }

        $modulos =
            $this->moduloStorage
            ->obtenerTodos();

        $indiceModulo = null;
        $moduloExistente = null;

        foreach (
            $modulos as
            $indice => $modulo
        ) {
            if (
                (int) (
                    $modulo['id_modulo']
                    ?? 0
                ) === $idModulo
            ) {
                $indiceModulo =
                    $indice;

                $moduloExistente =
                    $modulo;

                break;
            }
        }

        if (
            $indiceModulo === null ||
            $moduloExistente === null
        ) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró el módulo solicitado.',
                ]);
        }

        $idSistema =
            (int) (
                $datos['id_sistema']
                ?? (
                    $moduloExistente['id_sistema']
                    ?? 0
                )
            );

        if ($idSistema <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' => false,

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
                    'ok' => false,

                    'mensaje' =>
                    'El sistema asociado no existe.',
                ]);
        }

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
                    'ok' => false,

                    'mensaje' =>
                    'El nombre del módulo es obligatorio.',
                ]);
        }

        $moduloActualizado = [
            'id_modulo' =>
            $idModulo,

            'id_sistema' =>
            $idSistema,

            'nombre' =>
            $nombre,

            'tipo' =>
            trim(
                (string) (
                    $datos['tipo']
                    ?? ''
                )
            ),

            'descripcion' =>
            trim(
                (string) (
                    $datos['descripcion']
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

            /*
         * Conservamos el estado actual.
         */
            'activo' =>
            (bool) (
                $moduloExistente['activo']
                ?? true
            ),
        ];

        $modulos[$indiceModulo] =
            $moduloActualizado;

        $this->moduloStorage
            ->guardarTodos(
                $modulos
            );

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Módulo actualizado correctamente.',

                'modulo' =>
                $moduloActualizado,
            ]);
    }

    /*==================================================
    =                  ELIMINAR MÓDULO                 =
    ==================================================*/

    public function eliminar(
        int $idModulo
    ) {
        $modulos =
            $this->moduloStorage
            ->obtenerTodos();

        $moduloEncontrado = null;

        foreach ($modulos as $modulo) {
            if (
                (int) (
                    $modulo['id_modulo']
                    ?? 0
                ) === $idModulo
            ) {
                $moduloEncontrado =
                    $modulo;

                break;
            }
        }


        /*==================================================
        =              VALIDAR EXISTENCIA                  =
        ==================================================*/

        if ($moduloEncontrado === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No se encontró el módulo solicitado.',
                ]);
        }


        /*==================================================
        =              OBTENER SISTEMA                     =
        ==================================================*/

        $idSistema =
            (int) (
                $moduloEncontrado['id_sistema']
                ?? 0
            );


        /*==================================================
        =                ELIMINAR                          =
        ==================================================*/

        $modulos =
            array_values(
                array_filter(
                    $modulos,
                    static fn(array $modulo): bool =>
                    (int) (
                        $modulo['id_modulo']
                        ?? 0
                    ) !== $idModulo
                )
            );

        $this->moduloStorage
            ->guardarTodos(
                $modulos
            );


        /*==================================================
        =             TOTAL ACTUALIZADO                    =
        ==================================================*/

        $totalModulos =
            count(
                $this->moduloStorage
                    ->obtenerPorSistema(
                        $idSistema
                    )
            );


        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'Módulo eliminado correctamente.',

                'id_modulo' =>
                $idModulo,

                'id_sistema' =>
                $idSistema,

                'total_modulos' =>
                $totalModulos,
            ]);
    }
}
