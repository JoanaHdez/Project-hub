<?php

namespace App\Modules\Sistemas\Services;

class Sistema_StorageService
{
    private string $rutaArchivo;

    public function __construct()
    {
        $this->rutaArchivo =
            APPPATH . 'Modules/Sistemas/Data/sistemas.json';
    }

    public function obtenerTodos(): array
    {
        if (!is_file($this->rutaArchivo)) {
            return [];
        }

        $contenido = file_get_contents($this->rutaArchivo);

        if ($contenido === false || trim($contenido) === '') {
            return [];
        }

        $sistemas = json_decode($contenido, true);

        if (!is_array($sistemas)) {
            return [];
        }

        return $sistemas;
    }

    public function obtenerPorProyecto(int $idProyecto): array
    {
        $sistemas = $this->obtenerTodos();

        return array_values(
            array_filter(
                $sistemas,
                static fn(array $sistema): bool =>
                    (int) ($sistema['id_proyecto'] ?? 0) === $idProyecto
            )
        );
    }

    public function obtenerPorId(int $idSistema): ?array
    {
        foreach ($this->obtenerTodos() as $sistema) {
            if (
                (int) ($sistema['id_sistema'] ?? 0)
                === $idSistema
            ) {
                return $sistema;
            }
        }

        return null;
    }

    public function guardarTodos(array $sistemas): void
    {
        $directorio = dirname($this->rutaArchivo);

        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }

        file_put_contents(
            $this->rutaArchivo,
            json_encode(
                $sistemas,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),
            LOCK_EX
        );
    }

    public function generarNuevoId(array $sistemas): int
    {
        $ids = array_map(
            static fn(array $sistema): int =>
                (int) ($sistema['id_sistema'] ?? 0),
            $sistemas
        );

        return empty($ids)
            ? 1
            : max($ids) + 1;
    }
}