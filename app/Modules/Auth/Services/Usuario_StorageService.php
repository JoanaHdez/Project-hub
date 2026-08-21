<?php

namespace App\Modules\Auth\Services;

class Usuario_StorageService
{
    private string $rutaArchivo;


    public function __construct()
    {
        $this->rutaArchivo =
            APPPATH
            . 'Modules/Auth/Data/usuarios.json';

        $this->inicializarUsuarios();
    }


    /*==================================================
    =                 OBTENER TODOS                   =
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

        $usuarios =
            json_decode(
                $contenido,
                true
            );

        return is_array($usuarios)
            ? $usuarios
            : [];
    }


    /*==================================================
    =               OBTENER POR CORREO                =
    ==================================================*/

    public function obtenerPorCorreo(
        string $correo
    ): ?array {
        $correo =
            strtolower(
                trim($correo)
            );

        foreach (
            $this->obtenerTodos()
            as $usuario
        ) {

            $correoUsuario =
                strtolower(
                    trim(
                        (string) (
                            $usuario['correo']
                            ?? ''
                        )
                    )
                );

            if ($correoUsuario === $correo) {
                return $usuario;
            }
        }

        return null;
    }


    /*==================================================
    =              USUARIOS INICIALES                 =
    ==================================================*/

    private function inicializarUsuarios(): void
    {
        if (is_file($this->rutaArchivo)) {

            $contenido =
                file_get_contents(
                    $this->rutaArchivo
                );

            if (
                $contenido !== false &&
                trim($contenido) !== '' &&
                json_decode(
                    $contenido,
                    true
                )
            ) {
                return;
            }
        }

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


        $usuarios = [

            [
                'id_usuario' =>
                    1,

                'nombre' =>
                    'Administrador',

                'correo' =>
                    'admin@projecthub.local',

                'password_hash' =>
                    password_hash(
                        'Admin123!',
                        PASSWORD_DEFAULT
                    ),

                'rol' =>
                    'administrador',

                'activo' =>
                    true,
            ],

            [
                'id_usuario' =>
                    2,

                'nombre' =>
                    'Usuario',

                'correo' =>
                    'usuario@projecthub.local',

                'password_hash' =>
                    password_hash(
                        'Usuario123!',
                        PASSWORD_DEFAULT
                    ),

                'rol' =>
                    'usuario',

                'activo' =>
                    true,
            ],
        ];


        file_put_contents(
            $this->rutaArchivo,
            json_encode(
                $usuarios,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),
            LOCK_EX
        );
    }
}