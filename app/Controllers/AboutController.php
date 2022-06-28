<?php

namespace App\Controllers;

use App\Controllers\Base\BaseController;

class AboutController extends BaseController
{
    public function index()
    {
        return view('about/index');
    }
}
