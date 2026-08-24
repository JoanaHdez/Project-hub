<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;
use App\Modules\Auth\Services\Usuario_StorageService;


class Auth_Controller extends BaseController
{
    private Usuario_StorageService $usuarioStorage;


    public function __construct()
    {
        $this->usuarioStorage =
            new Usuario_StorageService();
    }


    /*==================================================
    =                     LOGIN                        =
    ==================================================*/

    public function login()
    {
        if (
            session()
                ->get('autenticado')
        ) {
            return $this->redirigirPorRol();
        }

        return view(
            'App\Modules\Auth\Views\login',
            [
                'title' =>
                    'Iniciar sesión | Project Hub',
            ]
        );
    }


    /*==================================================
    =                   AUTENTICAR                     =
    ==================================================*/

    public function autenticar()
    {
        $correo =
            strtolower(
                trim(
                    (string) $this->request
                        ->getPost('correo')
                )
            );

        $password =
            (string) $this->request
                ->getPost('password');


        /*==================================================
        =                   VALIDACIÓN                     =
        ==================================================*/

        if (
            $correo === '' ||
            $password === ''
        ) {

            return redirect()
                ->to(
                    base_url('login')
                )
                ->with(
                    'error',
                    'Ingresa tu correo y contraseña.'
                );
        }


        /*==================================================
        =               BUSCAR USUARIO                    =
        ==================================================*/

        $usuario =
            $this->usuarioStorage
                ->obtenerPorCorreo(
                    $correo
                );

        if (
            $usuario === null ||
            !($usuario['activo'] ?? false)
        ) {

            return redirect()
                ->to(
                    base_url('login')
                )
                ->with(
                    'error',
                    'El correo o la contraseña son incorrectos.'
                );
        }


        /*==================================================
        =              VALIDAR CONTRASEÑA                 =
        ==================================================*/

        $hash =
            (string) (
                $usuario['password_hash']
                ?? ''
            );

        if (
            $hash === '' ||
            !password_verify(
                $password,
                $hash
            )
        ) {

            return redirect()
                ->to(
                    base_url('login')
                )
                ->with(
                    'error',
                    'El correo o la contraseña son incorrectos.'
                );
        }


        /*==================================================
        =                  VALIDAR ROL                     =
        ==================================================*/

        $rol =
            (string) (
                $usuario['rol']
                ?? ''
            );

        $rolesPermitidos = [
            'superadministrador',
            'desarrollador',
        ];

        if (
            !in_array(
                $rol,
                $rolesPermitidos,
                true
            )
        ) {

            return redirect()
                ->to(
                    base_url('login')
                )
                ->with(
                    'error',
                    'Tu cuenta no tiene un rol válido para acceder a Project Hub.'
                );
        }


        /*==================================================
        =                  CREAR SESIÓN                    =
        ==================================================*/

        session()
            ->regenerate();

        session()
            ->set([
                'autenticado' =>
                    true,

                'id_usuario' =>
                    (int) (
                        $usuario['id_usuario']
                        ?? 0
                    ),

                'usuario_nombre' =>
                    $usuario['nombre']
                    ?? 'Usuario',

                'usuario_correo' =>
                    $usuario['correo']
                    ?? '',

                'usuario_rol' =>
                    $rol,
            ]);


        return $this->redirigirPorRol();
    }


    /*==================================================
    =                   CERRAR SESIÓN                   =
    ==================================================*/

    public function logout()
    {
        session()
            ->destroy();

        return redirect()
            ->to(
                base_url('login')
            );
    }


    /*==================================================
    =               REDIRECCIÓN POR ROL                =
    ==================================================*/

    private function redirigirPorRol()
    {
        $rol =
            (string) session()
                ->get('usuario_rol');


        /*==================================================
        =               SUPERADMINISTRADOR                =
        ==================================================*/

        if (
            $rol === 'superadministrador'
        ) {

            return redirect()
                ->to(
                    base_url('/')
                );
        }


        /*==================================================
        =                  DESARROLLADOR                  =
        ==================================================*/

        if (
            $rol === 'desarrollador'
        ) {

            /*
             * El desarrollador entra al mismo Project Hub.
             *
             * Los permisos para editar, eliminar,
             * activar o desactivar registros ajenos
             * se validarán posteriormente mediante
             * id_usuario_creador.
             */

            return redirect()
                ->to(
                    base_url('/')
                );
        }


        /*==================================================
        =                   ROL INVÁLIDO                  =
        ==================================================*/

        session()
            ->destroy();

        return redirect()
            ->to(
                base_url('login')
            )
            ->with(
                'error',
                'Tu cuenta no tiene un rol válido para acceder a Project Hub.'
            );
    }
}