<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Models\Usuario_Model;

class Usuario_StorageService
{
    private Usuario_Model $model;


    public function __construct()
    {
        $this->model =
            new Usuario_Model();
    }


    /*==================================================
    =                 OBTENER TODOS                   =
    ==================================================*/

    public function obtenerTodos(): array
    {
        $usuarios =
            $this->model
            ->obtenerTodosCompletos();


        return array_map(
            fn(array $usuario): array =>
                $this->prepararUsuario(
                    $usuario
                ),
            $usuarios
        );
    }


    /*==================================================
    =               OBTENER POR ID                    =
    ==================================================*/

    public function obtenerPorId(
        int $idUsuario
    ): ?array {

        $usuario =
            $this->model
            ->obtenerCompletoPorId(
                $idUsuario
            );


        if ($usuario === null) {
            return null;
        }


        return $this->prepararUsuario(
            $usuario
        );
    }


    /*==================================================
    =               OBTENER POR CORREO                =
    ==================================================*/

    public function obtenerPorCorreo(
        string $correo
    ): ?array {

        $correo =
            strtolower(
                trim(
                    $correo
                )
            );


        if ($correo === '') {
            return null;
        }


        $usuario =
            $this->model
            ->obtenerPorCorreo(
                $correo
            );


        if ($usuario === null) {
            return null;
        }


        return $this->prepararUsuario(
            $usuario
        );
    }


    /*==================================================
    =                PREPARAR USUARIO                 =
    ==================================================*/

    private function prepararUsuario(
        array $usuario
    ): array {

        $usuario['id_usuario'] =
            (int) (
                $usuario[
                    'id_usuario'
                ]
                ?? 0
            );


        $usuario['id_rol'] =
            (int) (
                $usuario[
                    'id_rol'
                ]
                ?? 0
            );


        $usuario['activo'] =
            (bool) (
                $usuario[
                    'activo'
                ]
                ?? false
            );


        $usuario['nombre'] =
            $usuario[
                'nombre'
            ]
            ?? '';


        $usuario['apellido_paterno'] =
            $usuario[
                'apellido_paterno'
            ]
            ?? '';


        $usuario['apellido_materno'] =
            $usuario[
                'apellido_materno'
            ]
            ?? '';


        $usuario['correo'] =
            $usuario[
                'correo'
            ]
            ?? '';


        $usuario['password_hash'] =
            $usuario[
                'password_hash'
            ]
            ?? '';


        $usuario['rol'] =
            $usuario[
                'rol'
            ]
            ?? '';


        return $usuario;
    }
}