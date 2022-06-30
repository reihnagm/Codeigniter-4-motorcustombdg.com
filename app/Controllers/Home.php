<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return view('home/indexv2');
    }
}
