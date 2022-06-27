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
            ON u.uid = p.user_uid INNER JOIN product_files pf ON p.uid = pf.product_uid 
            GROUP BY p.uid");
            $resultTotal = $limit > 10 ? ceil(count($resultCountProducts->getResult()) / $limit) : count($resultCountProducts->getResult());
            $perPage = ceil($resultTotal / $limit);
            $prevPage = $page === 1 ? 1 : $page - 1;
            $nextPage = $page === $perPage ? 1 : $page + 1;
            $queryProducts = $db->query("SELECT p.uid, p.slug, p.title, p.description, u.username, GROUP_CONCAT(pf.url) AS files, GROUP_CONCAT(pt.name) AS types
            FROM products p 
            INNER JOIN users u 
            ON u.uid = p.user_uid 
            INNER JOIN product_files pf 
            ON p.uid = pf.product_uid 
            INNER JOIN product_types pt
            ON pf.type = pt.id 
            GROUP BY p.uid
            LIMIT $offset, $limit");
            $products = $queryProducts->getResult();

            $data = [];

            foreach ($products as $key => $value) {
                $nestedData["uid"] = $value->uid;
                $nestedData["title"] = $value->title;
                $nestedData["slug"] = $value->slug;
                $nestedData["description"] = $value->description;
                $nestedData["username"] = $value->username;

                $files = [];
           
                foreach (explode(",", $value->files) as $key => $val) {
                    $files[] = [
                        "url" => $val,
                        "type" => explode(",", $value->types)[$key]
                    ];
                }
                
                $nestedData["files"] = $files;
    
                $data[] = $nestedData;
            }
            
            $hasNext = $nextPage == $perPage ? true : false;
                        
            return $this->respond([
                "code" => 200,
                "message" => "Successfully Fetch Products",
                "data" => $data,
                "total" => $resultTotal,
                "perPage" => $perPage,
                "nextPage" => $nextPage,
                "prevPage" => $prevPage,
                "currentPage" => $page,
                "hasNext" => $hasNext,
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
        $queryResultProducts = $db->query("SELECT p.*, u.username, GROUP_CONCAT(pf.url) AS files, GROUP_CONCAT(pt.name) AS types 
        FROM products p 
        INNER JOIN users u 
        ON u.uid = p.user_uid 
        LEFT JOIN product_files pf 
        ON p.uid = pf.product_uid 
        LEFT JOIN product_types pt
        ON pf.type = pt.id
        WHERE p.slug = '$slug'
        GROUP BY p.uid ");

        $products = $queryResultProducts->getResult();

        if(!empty($products)) {
            $data["title"] = $products[0]->title;
            $data["description"] = $products[0]->description;
            $files = [];
            foreach (explode(',', $products[0]->files) as $key => $val) {
                $files[] = [
                    "url" => $val,
                    "type" => explode(',', $products[0]->types)[$key] 
                ];
            }
            $data["files"] = $files;
        } else {
            $data["title"] = "-";
            $data["description"] = "-";
            $data["files"] = [];
        }

        return view('products/detail', $data);
    }

}