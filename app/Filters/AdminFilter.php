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
        =                  VALIDAR ROL                    =
        ==================================================*/

        if (
            session()
                ->get('usuario_rol')
            !== 'administrador'
        ) {
            return redirect()
                ->to(
                    base_url('mis-sistemas')
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