<?php

namespace App\Modules\Proyectos\Services;

class Especificacion_StorageService
{
    private string $rutaArchivo;

    public function __construct()
    {
        $this->rutaArchivo =
            APPPATH
            . 'Modules/Proyectos/Data/especificaciones.json';
    }


    /*==================================================
    =                  OBTENER TODAS                   =
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

        $especificaciones =
            json_decode(
                $contenido,
                true
            );

        if (!is_array($especificaciones)) {
            return [];
        }

        return $especificaciones;
    }


    /*==================================================
    =                   OBTENER POR ID                 =
    ==================================================*/

    public function obtenerPorId(
        int $idEspecificacion
    ): ?array {
        foreach (
            $this->obtenerTodos()
            as $especificacion
        ) {
            if (
                (int) (
                    $especificacion[
                        'id_especificacion'
                    ] ?? 0
                ) === $idEspecificacion
            ) {
                return $especificacion;
            }
        }

        return null;
    }


    /*==================================================
    =                   GUARDAR TODAS                  =
    ==================================================*/

    public function guardarTodos(
        array $especificaciones
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
                $especificaciones,
                JSON_PRETTY_PRINT
                    | JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
            ),
            LOCK_EX
        );
    }


    /*==================================================
    =                  GENERAR NUEVO ID                =
    ==================================================*/

    public function generarNuevoId(
        array $especificaciones
    ): int {
        $ids =
            array_map(
                static fn(
                    array $especificacion
                ): int =>
                    (int) (
                        $especificacion[
                            'id_especificacion'
                        ] ?? 0
                    ),
                $especificaciones
            );

        return empty($ids)
            ? 1
            : max($ids) + 1;
    }
}