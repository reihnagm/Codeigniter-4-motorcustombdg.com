<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Config\Database;

use App\Controllers\Base\BaseController;

class ProductController extends BaseController {
    use ResponseTrait;

    public function initProducts() {
        $db = Database::connect();
        $req = Services::request();
        $page = (int) $req->getVar("page") ?: 1;
        $limit = (int) $req->getVar("limit") ?: 10;
        $offset = ($page - 1) * $limit;
        try {
            $resultCountProducts = $db->query("SELECT * FROM products p 
            INNER JOIN users u 
            ON u.uid = p.user_uid INNER JOIN product_imgs prm ON p.uid = prm.product_uid 
            GROUP BY p.uid");
            $resultTotal = $limit > 10 ? ceil(count($resultCountProducts->getResult()) / $limit) : count($resultCountProducts->getResult());
            $perPage = ceil($resultTotal / $limit);
            $prevPage = $page === 1 ? 1 : $page - 1;
            $nextPage = $page === $perPage ? 1 : $page + 1;
            $queryProducts = $db->query("SELECT p.*, u.username, GROUP_CONCAT(prm.img) AS images 
            FROM products p 
            INNER JOIN users u 
            ON u.uid = p.user_uid 
            INNER JOIN product_imgs prm 
            ON p.uid = prm.product_uid 
            GROUP BY p.uid
            LIMIT $offset, $limit");
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
    
    public function detail($slug) {
        $db = Database::connect();
        $queryResultProducts = $db->query("SELECT p.*, u.username, GROUP_CONCAT(prm.img) AS images FROM products p 
        INNER JOIN users u 
        ON u.uid = p.user_uid 
        INNER JOIN product_imgs prm 
        ON p.uid = prm.product_uid 
        WHERE p.slug = '$slug'
        GROUP BY p.uid ");

        $products = $queryResultProducts->getResult();

        if(!empty($products)) {
            $data["title"] = $products[0]->title;
            $data["description"] = $products[0]->description;
            $data["images"] = explode(',', $products[0]->images);
        } else {
            $data["title"] = "-";
            $data["description"] = "-";
            $data["images"] = [];
        }

        return view('products/detail', $data);
    }

}