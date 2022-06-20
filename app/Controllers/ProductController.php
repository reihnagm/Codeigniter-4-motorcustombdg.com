<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Services;

use App\Controllers\Base\BaseController;

class ProductController extends BaseController {
    use ResponseTrait;

    public function index() { 
        die(var_dump("hello"));
    }

}