<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        /*==================================================
        =              VALIDAR AUTENTICACIÓN              =
        ==================================================*/

        if (
            !session()
                ->get('autenticado')
        ) {
            return redirect()
                ->to(
                    base_url('login')
                );
        }


        /*==================================================
        =                  VALIDAR ROL                     =
        ==================================================*/

        $rol =
            (string) session()
                ->get('usuario_rol');


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
            session()
                ->destroy();

            return redirect()
                ->to(
                    base_url('login')
                )
                ->with(
                    'error',
                    'No tienes permisos para acceder a Project Hub.'
                );
        }
    }


    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        //
    }
}