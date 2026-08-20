<?php

namespace App\Services;


class Actividad_StorageService
{
    private string $rutaArchivo;


    public function __construct()
    {
        $this->rutaArchivo =
            APPPATH
            . 'Data/actividad.json';
    }


    /*==================================================
    =              OBTENER TODAS                     =
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

        $actividades =
            json_decode(
                $contenido,
                true
            );

        if (!is_array($actividades)) {
            return [];
        }

        return $actividades;
    }


    /*==================================================
    =              OBTENER RECIENTES                 =
    ==================================================*/

    public function obtenerRecientes(
        int $limite = 10
    ): array {
        $actividades =
            $this->obtenerTodos();

        usort(
            $actividades,
            static function (
                array $a,
                array $b
            ): int {
                return strcmp(
                    (string) (
                        $b['fecha_hora']
                        ?? ''
                    ),
                    (string) (
                        $a['fecha_hora']
                        ?? ''
                    )
                );
            }
        );

        return array_slice(
            $actividades,
            0,
            $limite
        );
    }


    /*==================================================
    =              REGISTRAR ACTIVIDAD               =
    ==================================================*/

    public function registrar(
        array $datos
    ): array {
        $actividades =
            $this->obtenerTodos();

        $actividad = [

            'id_actividad' =>
                $this->generarNuevoId(
                    $actividades
                ),

            /*
             * Temporal hasta integrar
             * autenticación y usuarios.
             */
            'id_usuario' =>
                $datos['id_usuario']
                ?? null,

            'usuario_nombre' =>
                trim(
                    (string) (
                        $datos['usuario_nombre']
                        ?? 'Usuario actual'
                    )
                ),

            'bloque' =>
                trim(
                    (string) (
                        $datos['bloque']
                        ?? ''
                    )
                ),

            'accion' =>
                trim(
                    (string) (
                        $datos['accion']
                        ?? ''
                    )
                ),

            'entidad_tipo' =>
                trim(
                    (string) (
                        $datos['entidad_tipo']
                        ?? ''
                    )
                ),

            'entidad_id' =>
                $datos['entidad_id']
                ?? null,

            'detalle' =>
                trim(
                    (string) (
                        $datos['detalle']
                        ?? ''
                    )
                ),

            'fecha_hora' =>
                date(
                    'Y-m-d H:i:s'
                ),
        ];

        $actividades[] =
            $actividad;

        $this->guardarTodos(
            $actividades
        );

        return $actividad;
    }


    /*==================================================
    =              GUARDAR TODAS                     =
    ==================================================*/

    private function guardarTodos(
        array $actividades
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
                $actividades,
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

    private function generarNuevoId(
        array $actividades
    ): int {
        $ids =
            array_map(
                static fn(array $actividad): int =>
                    (int) (
                        $actividad['id_actividad']
                        ?? 0
                    ),
                $actividades
            );

        return empty($ids)
            ? 1
            : max($ids) + 1;
    }
}