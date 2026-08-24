<?php

namespace App\Modules\Modulos\Services;

use App\Modules\Modulos\Models\Modulo_Model;

class Modulo_StorageService
{
    private Modulo_Model $model;

    public function __construct()
    {
        $this->model =
            new Modulo_Model();
    }


    /*==================================================
    =                  OBTENER TODOS                  =
    ==================================================*/

    public function obtenerTodos(): array
    {
        $modulos =
            $this->model
            ->obtenerTodosCompletos();


        return array_map(
            fn(array $modulo): array =>
                $this->prepararModulo(
                    $modulo
                ),
            $modulos
        );
    }


    /*==================================================
    =                  OBTENER POR ID                 =
    ==================================================*/

    public function obtenerPorId(
        int $idModulo
    ): ?array {

        $modulo =
            $this->model
            ->obtenerCompletoPorId(
                $idModulo
            );


        if ($modulo === null) {
            return null;
        }


        return $this->prepararModulo(
            $modulo
        );
    }


    /*==================================================
    =              OBTENER POR SISTEMA                =
    ==================================================*/

    public function obtenerPorSistema(
        int $idSistema
    ): array {

        $modulos =
            $this->model
            ->obtenerCompletosPorSistema(
                $idSistema
            );


        return array_map(
            fn(array $modulo): array =>
                $this->prepararModulo(
                    $modulo
                ),
            $modulos
        );
    }


    /*==================================================
    =                     CREAR                       =
    ==================================================*/

    public function crear(
        array $datos
    ): ?array {

        $datosBd =
            $this->prepararDatosBd(
                $datos
            );


        $idUsuarioCreador =
            (int) session()
                ->get('id_usuario');

        if ($idUsuarioCreador <= 0) {
            throw new \RuntimeException(
                'No se pudo identificar al usuario creador del módulo.'
            );
        }

        $datosBd['id_usuario_creador'] =
            $idUsuarioCreador;

        $datosBd['activo'] =
            1;


        $idModulo =
            $this->model
            ->insert(
                $datosBd,
                true
            );


        if (!$idModulo) {
            return null;
        }


        return $this->obtenerPorId(
            (int) $idModulo
        );
    }


    /*==================================================
    =                   ACTUALIZAR                    =
    ==================================================*/

    public function actualizar(
        int $idModulo,
        array $datos
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idModulo
            );


        if (!is_array($existente)) {
            return null;
        }


        /*
         * Si no se envía id_sistema durante
         * la edición, conservamos el actual.
         */
        if (
            !isset(
                $datos['id_sistema']
            )
            ||
            (int) $datos['id_sistema'] <= 0
        ) {

            $datos['id_sistema'] =
                (int) (
                    $existente['id_sistema']
                    ?? 0
                );
        }


        $datosBd =
            $this->prepararDatosBd(
                $datos
            );


        /*
         * La imagen no se modifica desde
         * la edición general del módulo.
         */
        unset(
            $datosBd['imagen']
        );


        $actualizado =
            $this->model
            ->update(
                $idModulo,
                $datosBd
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idModulo
        );
    }


    /*==================================================
    =              ACTUALIZAR IMAGEN                 =
    ==================================================*/

    public function actualizarImagen(
        int $idModulo,
        string $rutaImagen
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idModulo
            );


        if (!is_array($existente)) {
            return null;
        }


        $actualizado =
            $this->model
            ->update(
                $idModulo,
                [
                    'imagen' =>
                        $rutaImagen,
                ]
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idModulo
        );
    }


    /*==================================================
    =                 CAMBIAR ESTADO                  =
    ==================================================*/

    public function cambiarEstado(
        int $idModulo,
        bool $activo
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idModulo
            );


        if (!is_array($existente)) {
            return null;
        }


        $actualizado =
            $this->model
            ->update(
                $idModulo,
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
            $idModulo
        );
    }


    /*==================================================
    =                    ACTIVAR                      =
    ==================================================*/

    public function activar(
        int $idModulo
    ): ?array {

        return $this->cambiarEstado(
            $idModulo,
            true
        );
    }


    /*==================================================
    =                  DESACTIVAR                     =
    ==================================================*/

    public function desactivar(
        int $idModulo
    ): ?array {

        return $this->cambiarEstado(
            $idModulo,
            false
        );
    }


    /*==================================================
    =                    ELIMINAR                     =
    ==================================================*/

    public function eliminar(
        int $idModulo
    ): bool {

        $existente =
            $this->model
            ->find(
                $idModulo
            );


        if (!is_array($existente)) {
            return false;
        }


        return $this->model
            ->delete(
                $idModulo
            );
    }


    /*==================================================
    =              PREPARAR DATOS BD                 =
    ==================================================*/

    private function prepararDatosBd(
        array $datos
    ): array {

        $idSistema =
            (int) (
                $datos['id_sistema']
                ?? 0
            );


        if ($idSistema <= 0) {

            throw new \RuntimeException(
                'El sistema asociado es obligatorio.'
            );
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
                'El nombre del módulo es obligatorio.'
            );
        }


        return [

            'id_sistema' =>
                $idSistema,

            'tipo' =>
                $this->normalizarNullable(
                    $datos['tipo']
                    ?? null
                ),

            'nombre' =>
                $nombre,

            'descripcion' =>
                $this->normalizarNullable(
                    $datos['descripcion']
                    ?? null
                ),

            'url' =>
                $this->normalizarNullable(
                    $datos['url']
                    ?? null
                ),

            /*
             * Solamente tendrá valor al crear
             * si expresamente se proporciona.
             */
            'imagen' =>
                $this->normalizarNullable(
                    $datos['imagen']
                    ?? null
                ),
        ];
    }


    /*==================================================
    =                PREPARAR MÓDULO                  =
    ==================================================*/

    private function prepararModulo(
        array $modulo
    ): array {

        $modulo['id_modulo'] =
            (int) (
                $modulo['id_modulo']
                ?? 0
            );


        $modulo['id_sistema'] =
            (int) (
                $modulo['id_sistema']
                ?? 0
            );


        if (
            isset(
                $modulo['id_proyecto']
            )
        ) {

            $modulo['id_proyecto'] =
                (int)
                $modulo['id_proyecto'];
        }


        $modulo['activo'] =
            (bool) (
                $modulo['activo']
                ?? false
            );


        /*
         * Conservamos cadenas vacías para que
         * el frontend actual siga funcionando
         * igual que cuando utilizaba JSON.
         */
        $modulo['tipo'] =
            $modulo['tipo']
            ?? '';

        $modulo['descripcion'] =
            $modulo['descripcion']
            ?? '';

        $modulo['url'] =
            $modulo['url']
            ?? '';

        $modulo['imagen'] =
            $modulo['imagen']
            ?? '';


        return $modulo;
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