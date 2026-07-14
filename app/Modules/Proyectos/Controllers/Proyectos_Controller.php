<?php

namespace App\Modules\Proyectos\Controllers;

use App\Controllers\BaseController;

class Proyectos_Controller extends BaseController
{
    public function index()
    {
        return view('App\Modules\Proyectos\Views\index', [
            'title' => 'Proyectos | Project Hub',
        ]);
    }
}

