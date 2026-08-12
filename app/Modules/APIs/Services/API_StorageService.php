<?php

namespace App\Modules\APIs\Services;

class API_StorageService
{
    private string $rutaArchivo;

    public function __construct()
    {
        $this->rutaArchivo =
            APPPATH . 'Modules/APIs/Data/apis.json';
    }

    public function obtenerTodos(): array
    {
        if (!is_file($this->rutaArchivo)) {
            return [];
        }

        $contenido = file_get_contents(
            $this->rutaArchivo
        );

        if (
            $contenido === false
            || trim($contenido) === ''
        ) {
            return [];
        }

        $apis = json_decode(
            $contenido,
            true
        );

        if (!is_array($apis)) {
            return [];
        }

        return $apis;
    }

    public function obtenerPorId(
        int $idApi
    ): ?array {
        foreach ($this->obtenerTodos() as $api) {
            if (
                (int) ($api['id_api'] ?? 0)
                === $idApi
            ) {
                return $api;
            }
        }

        return null;
    }

    public function obtenerPorProyecto(
        int $idProyecto
    ): array {
        return array_values(
            array_filter(
                $this->obtenerTodos(),
                static fn(array $api): bool =>
                    (int) (
                        $api['id_proyecto'] ?? 0
                    ) === $idProyecto
            )
        );
    }

    public function guardarTodos(
        array $apis
    ): void {
        $directorio = dirname(
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
                $apis,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),
            LOCK_EX
        );
    }

    public function generarNuevoId(
        array $apis
    ): int {
        $ids = array_map(
            static fn(array $api): int =>
                (int) ($api['id_api'] ?? 0),
            $apis
        );

        return empty($ids)
            ? 1
            : max($ids) + 1;
    }
}