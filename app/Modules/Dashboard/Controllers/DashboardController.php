<?php

namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('App\Modules\Dashboard\Views\index', [
            'title' => 'Dashboard | Project Hub',
        ]);
    }
}