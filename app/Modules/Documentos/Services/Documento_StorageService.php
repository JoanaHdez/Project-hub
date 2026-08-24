<?php

namespace App\Modules\Documentos\Services;

use App\Modules\Documentos\Models\Documento_Model;

class Documento_StorageService
{
    private Documento_Model $model;

    public function __construct()
    {
        $this->model =
            new Documento_Model();
    }


    /*==================================================
    =              OBTENER TODOS                      =
    ==================================================*/

    public function obtenerTodos(): array
    {
        $documentos =
            $this->model
            ->obtenerTodosCompletos();


        return array_map(
            fn(array $documento): array =>
                $this->prepararDocumento(
                    $documento
                ),
            $documentos
        );
    }


    /*==================================================
    =              OBTENER POR ID                     =
    ==================================================*/

    public function obtenerPorId(
        int $idDocumento
    ): ?array {

        $documento =
            $this->model
            ->obtenerCompletoPorId(
                $idDocumento
            );


        if ($documento === null) {
            return null;
        }


        return $this->prepararDocumento(
            $documento
        );
    }


    /*==================================================
    =              OBTENER POR SISTEMA                =
    ==================================================*/

    public function obtenerPorSistema(
        int $idSistema
    ): array {

        $documentos =
            $this->model
            ->obtenerCompletosPorSistema(
                $idSistema
            );


        return array_map(
            fn(array $documento): array =>
                $this->prepararDocumento(
                    $documento
                ),
            $documentos
        );
    }


    /*==================================================
    =                    CREAR                        =
    ==================================================*/

    public function crear(
        array $datos
    ): ?array {

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


        $nombreOriginal =
            trim(
                (string) (
                    $datos['nombre_original']
                    ?? ''
                )
            );


        if ($nombreOriginal === '') {

            throw new \RuntimeException(
                'El nombre original del documento es obligatorio.'
            );
        }


        $nombreArchivo =
            trim(
                (string) (
                    $datos['nombre_archivo']
                    ?? ''
                )
            );


        if ($nombreArchivo === '') {

            throw new \RuntimeException(
                'El nombre interno del archivo es obligatorio.'
            );
        }


        $rutaArchivo =
            trim(
                (string) (
                    $datos['ruta_archivo']
                    ?? (
                        $datos['ruta']
                        ?? ''
                    )
                )
            );


        if ($rutaArchivo === '') {

            throw new \RuntimeException(
                'La ruta del documento es obligatoria.'
            );
        }


        $datosBd = [

            'id_sistema' =>
                $idSistema,

            'id_usuario_creador' =>
                $this->obtenerIdUsuarioActual(),

            'nombre_original' =>
                $nombreOriginal,

            'nombre_archivo' =>
                $nombreArchivo,

            'tipo_mime' =>
                $this->normalizarNullable(
                    $datos['tipo_mime']
                    ?? null
                ),

            'extension' =>
                $this->normalizarNullable(
                    $datos['extension']
                    ?? null
                ),

            'tamano' =>
                max(
                    0,
                    (int) (
                        $datos['tamano']
                        ?? 0
                    )
                ),

            'ruta_archivo' =>
                $rutaArchivo,

            'descripcion' =>
                $this->normalizarNullable(
                    $datos['descripcion']
                    ?? null
                ),
        ];


        $idDocumento =
            $this->model
            ->insert(
                $datosBd,
                true
            );


        if (!$idDocumento) {
            return null;
        }


        return $this->obtenerPorId(
            (int) $idDocumento
        );
    }


    /*==================================================
    =                ACTUALIZAR RUTA                  =
    ==================================================*/

    public function actualizarArchivo(
        int $idDocumento,
        array $datos
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idDocumento
            );


        if (!is_array($existente)) {
            return null;
        }


        $datosBd = [];


        if (
            array_key_exists(
                'nombre_archivo',
                $datos
            )
        ) {

            $datosBd['nombre_archivo'] =
                trim(
                    (string)
                    $datos['nombre_archivo']
                );
        }


        if (
            array_key_exists(
                'ruta_archivo',
                $datos
            )
            ||
            array_key_exists(
                'ruta',
                $datos
            )
        ) {

            $datosBd['ruta_archivo'] =
                trim(
                    (string) (
                        $datos['ruta_archivo']
                        ?? $datos['ruta']
                        ?? ''
                    )
                );
        }


        if (
            array_key_exists(
                'tipo_mime',
                $datos
            )
        ) {

            $datosBd['tipo_mime'] =
                $this->normalizarNullable(
                    $datos['tipo_mime']
                );
        }


        if (
            array_key_exists(
                'extension',
                $datos
            )
        ) {

            $datosBd['extension'] =
                $this->normalizarNullable(
                    $datos['extension']
                );
        }


        if (
            array_key_exists(
                'tamano',
                $datos
            )
        ) {

            $datosBd['tamano'] =
                max(
                    0,
                    (int)
                    $datos['tamano']
                );
        }


        if (empty($datosBd)) {

            return $this->obtenerPorId(
                $idDocumento
            );
        }


        $actualizado =
            $this->model
            ->update(
                $idDocumento,
                $datosBd
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idDocumento
        );
    }


    /*==================================================
    =                  ACTUALIZAR                     =
    ==================================================*/

    public function actualizar(
        int $idDocumento,
        array $datos
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idDocumento
            );


        if (!is_array($existente)) {
            return null;
        }


        $datosBd = [];


        if (
            array_key_exists(
                'descripcion',
                $datos
            )
        ) {

            $datosBd['descripcion'] =
                $this->normalizarNullable(
                    $datos['descripcion']
                );
        }


        if (empty($datosBd)) {

            return $this->obtenerPorId(
                $idDocumento
            );
        }


        $actualizado =
            $this->model
            ->update(
                $idDocumento,
                $datosBd
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idDocumento
        );
    }


    /*==================================================
    =                    ELIMINAR                     =
    ==================================================*/

    public function eliminar(
        int $idDocumento
    ): bool {

        $existente =
            $this->model
            ->find(
                $idDocumento
            );


        if (!is_array($existente)) {
            return false;
        }


        return $this->model
            ->delete(
                $idDocumento
            );
    }


    /*==================================================
    =              PREPARAR DOCUMENTO                 =
    ==================================================*/

    private function prepararDocumento(
        array $documento
    ): array {

        $documento['id_documento'] =
            (int) (
                $documento['id_documento']
                ?? 0
            );


        $documento['id_sistema'] =
            (int) (
                $documento['id_sistema']
                ?? 0
            );


        if (
            isset(
                $documento['id_proyecto']
            )
        ) {

            $documento['id_proyecto'] =
                (int)
                $documento['id_proyecto'];
        }


        /*
         * Alias para conservar compatibilidad
         * con el frontend actual.
         */
        $documento['ruta'] =
            $documento['ruta_archivo']
            ?? '';


        $documento['fecha_subida'] =
            $documento['created_at']
            ?? '';


        /*
         * Antes el JSON guardaba "tipo" como
         * extensión en mayúsculas.
         */
        $extension =
            trim(
                (string) (
                    $documento['extension']
                    ?? ''
                )
            );


        $documento['tipo'] =
            $extension !== ''
                ? strtoupper(
                    $extension
                )
                : (
                    $documento['tipo_mime']
                    ?? ''
                );


        $documento['nombre_original'] =
            $documento['nombre_original']
            ?? '';

        $documento['nombre_archivo'] =
            $documento['nombre_archivo']
            ?? '';

        $documento['tipo_mime'] =
            $documento['tipo_mime']
            ?? '';

        $documento['extension'] =
            $documento['extension']
            ?? '';

        $documento['ruta_archivo'] =
            $documento['ruta_archivo']
            ?? '';

        $documento['descripcion'] =
            $documento['descripcion']
            ?? '';

        $documento['tamano'] =
            (int) (
                $documento['tamano']
                ?? 0
            );


        return $documento;
    }


    /*==================================================
    =              USUARIO AUTENTICADO                =
    ==================================================*/

    private function obtenerIdUsuarioActual(): int
    {
        $idUsuario =
            (int) session()
                ->get('id_usuario');

        if ($idUsuario <= 0) {
            throw new \RuntimeException(
                'No se pudo identificar al usuario creador del documento.'
            );
        }

        return $idUsuario;
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