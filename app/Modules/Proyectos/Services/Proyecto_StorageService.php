<?php

namespace App\Modules\Proyectos\Services;

class Proyecto_StorageService
{
    private string $rutaArchivo;

    public function __construct()
    {
        $this->rutaArchivo = APPPATH
            . 'Modules/Proyectos/Data/proyectos.json';
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

        $proyectos = json_decode($contenido, true);

        if (!is_array($proyectos)) {
            return [];
        }

        return $proyectos;
    }

    public function guardarTodos(array $proyectos): void
    {
        $directorio = dirname($this->rutaArchivo);

        if (!is_dir($directorio)) {
            mkdir($directorio, 0775, true);
        }

        file_put_contents(
            $this->rutaArchivo,
            json_encode(
                $proyectos,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ),
            LOCK_EX
        );
    }

    public function generarNuevoId(array $proyectos): int
    {
        $ids = array_map(
            static fn(array $proyecto): int =>
                (int) ($proyecto['id_proyecto'] ?? 0),
            $proyectos
        );

        return empty($ids)
            ? 1
            : max($ids) + 1;
    }
}