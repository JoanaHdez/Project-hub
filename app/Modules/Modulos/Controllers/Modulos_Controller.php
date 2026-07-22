<?php

namespace App\Modules\Modulos\Controllers;

use App\Controllers\BaseController;

class Modulos_Controller extends BaseController
{
    public function index()
    {
        return view('App\Modules\Modulos\Views\index');
    }
}