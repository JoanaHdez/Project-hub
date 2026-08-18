<?php

namespace App\Modules\Modulos\Services;

class Modulo_StorageService
{
    private string $rutaArchivo;

    public function __construct()
    {
        $this->rutaArchivo =
            APPPATH . 'Modules/Modulos/Data/modulos.json';
    }

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

        $modulos =
            json_decode(
                $contenido,
                true
            );

        if (!is_array($modulos)) {
            return [];
        }

        return $modulos;
    }

    public function obtenerPorId(
        int $idModulo
    ): ?array {
        foreach (
            $this->obtenerTodos() as
            $modulo
        ) {
            if (
                (int) (
                    $modulo['id_modulo']
                    ?? 0
                ) === $idModulo
            ) {
                return $modulo;
            }
        }

        return null;
    }

    public function obtenerPorSistema(
        int $idSistema
    ): array {
        return array_values(
            array_filter(
                $this->obtenerTodos(),
                static fn(array $modulo): bool =>
                    (int) (
                        $modulo['id_sistema']
                        ?? 0
                    ) === $idSistema
            )
        );
    }

    public function guardarTodos(
        array $modulos
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
                $modulos,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),
            LOCK_EX
        );
    }

    public function generarNuevoId(
        array $modulos
    ): int {
        $ids =
            array_map(
                static fn(array $modulo): int =>
                    (int) (
                        $modulo['id_modulo']
                        ?? 0
                    ),
                $modulos
            );

        return empty($ids)
            ? 1
            : max($ids) + 1;
    }
}