<?php

namespace App\Modules\Documentos\Services;


class Documento_StorageService
{
    private string $rutaArchivo;


    public function __construct()
    {
        $this->rutaArchivo =
            APPPATH
            . 'Modules/Documentos/Data/documentos.json';
    }


    /*==================================================
    =              OBTENER TODOS                      =
    ==================================================*/

    public function obtenerTodos(): array
    {
        if (!is_file($this->rutaArchivo)) {
            return [];
        }

        $contenido =
            file_get_contents(
                $this->rutaArchivo
            );

        if (
            $contenido === false ||
            trim($contenido) === ''
        ) {
            return [];
        }

        $documentos =
            json_decode(
                $contenido,
                true
            );

        if (!is_array($documentos)) {
            return [];
        }

        return $documentos;
    }


    /*==================================================
    =              OBTENER POR ID                     =
    ==================================================*/

    public function obtenerPorId(
        int $idDocumento
    ): ?array {
        foreach (
            $this->obtenerTodos() as
            $documento
        ) {
            if (
                (int) (
                    $documento['id_documento']
                    ?? 0
                ) === $idDocumento
            ) {
                return $documento;
            }
        }

        return null;
    }


    /*==================================================
    =              OBTENER POR SISTEMA                =
    ==================================================*/

    public function obtenerPorSistema(
        int $idSistema
    ): array {
        return array_values(
            array_filter(
                $this->obtenerTodos(),
                static fn(array $documento): bool =>
                    (int) (
                        $documento['id_sistema']
                        ?? 0
                    ) === $idSistema
            )
        );
    }


    /*==================================================
    =              GUARDAR TODOS                     =
    ==================================================*/

    public function guardarTodos(
        array $documentos
    ): void {
        $directorio =
            dirname(
                $this->rutaArchivo
            );

        if (!is_dir($directorio)) {
            mkdir(
                $directorio,
                0775,
                true
            );
        }

        file_put_contents(
            $this->rutaArchivo,
            json_encode(
                $documentos,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),
            LOCK_EX
        );
    }


    /*==================================================
    =              GENERAR NUEVO ID                  =
    ==================================================*/

    public function generarNuevoId(
        array $documentos
    ): int {
        $ids =
            array_map(
                static fn(array $documento): int =>
                    (int) (
                        $documento['id_documento']
                        ?? 0
                    ),
                $documentos
            );

        return empty($ids)
            ? 1
            : max($ids) + 1;
    }
}