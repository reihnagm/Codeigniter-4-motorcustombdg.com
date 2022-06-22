<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Config\Database;

use App\Controllers\Base\BaseController;

class ProductController extends BaseController {
    use ResponseTrait;

    public function index() { 


        die(var_dump("hello"));
    }

    public function initProducts() {
        $db = Database::connect();
        $req = Services::request();
        $page = (int) $req->getVar("page") ?: 1;
        $limit = (int) $req->getVar("limit") ?: 10;
        $offset = ($page - 1) * $limit;
        try {
            $resultCountProducts = $db->query("SELECT * FROM products");
            $resultTotal = $limit > 10 ? ceil(count($resultCountProducts->getResult()) / $limit) : count($resultCountProducts->getResult());
            $perPage = ceil($resultTotal / $limit);
            $prevPage = $page === 1 ? 1 : $page - 1;
            $nextPage = $page === $perPage ? 1 : $page + 1;
            $queryProducts = $db->query("SELECT a.*, u.username FROM products a INNER JOIN users u 
            ON u.uid = a.user_uid LIMIT $offset, $limit");
            $products = $queryProducts->getResult();
            
            return $this->respond([
                "code" => 200,
                "message" => "Successfully Fetch Products",
                "data" => $products,
                "total" => $resultTotal,
                "perPage" => $perPage,
                "nextPage" => $nextPage,
                "prevPage" => $prevPage,
                "currentPage" => $page,
                "hasNext" => $page == $perPage ? false : true,
                "nextUrl" => base_url(uri_string())."?page=".$nextPage,
                "prevUrl" => base_url(uri_string())."?page=".$prevPage,
            ], 200);
        } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);
        }
     }   

}