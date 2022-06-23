<?php

namespace App\Controllers\Admin;

use App\Controllers\Base\BaseController;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Config\Database;
use GuzzleHttp\Client;

class AdminController extends BaseController
{
    use ResponseTrait;

    public function index() {
        $db = Database::connect();
        $queryAllProduct = $db->query("SELECT p.*, u.username FROM products p 
        INNER JOIN users u ON u.uid = p.user_uid");
        $productCount = (int) count($queryAllProduct->getResult());
        $data["totalProduct"] = $productCount;
        return view('admin/index', $data);
    }

    public function products() {
        return view('admin/products/index');
    }

    public function productsUpload() {
        $request = Services::request();
        $filesCount = $request->getPost("filesCount");
        $data = [];
        for ($i = 0; $i < $filesCount; $i++) { 
            $filename = $_FILES["file-".$i]["name"];
            $x = explode('.', $filename);
            $extension = strtolower(end($x));
            $nestedData["filename"] = $x[0].'.'.$extension;
            $data[] = $nestedData;
            // $location = 'public/'.$filename;
            // move_uploaded_file($_FILES["file-".$i]["tmp_name"], $location);
        }
        return $this->respond([
            "error" => false,
            "code" => 200,
            "message" => "Successfully fetch files",
            "data" => $data
        ], 200);
    }

    public function productsDelete($uid) {
        $db = Database::connect();
        try {
            $queryProducts = $db->query("SELECT * FROM products WHERE uid = '$uid'");
            $products = $queryProducts->getResult();
            if(file_exists(FCPATH . $products[0]->img)) {
                unlink(FCPATH . $products[0]->img);
            }
            $db->simpleQuery("DELETE FROM products WHERE uid = '$uid'");
            return $this->respond([
                "error" => false,
                "code" => 200,
                "message" => "Successfully delete product",
            ], 200);
        } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function store() {
        $db = Database::connect();
        $request = Services::request();
        $session = Services::session();

        $uid = uuidv4();
        $title = $request->getPost("title");
        $description = $request->getPost("description");
        $filesCount = $request->getPost("filesCount");
        $useruid = $session->get("useruid");

        for ($i = 0; $i < $filesCount; $i++) { 
            $filename = $_FILES["file-".$i]["name"];
            $location = 'public/web/'.$filename;
            move_uploaded_file($_FILES["file-".$i]["tmp_name"], $location);

            $img = '/public/web/'.$filename;

            $queryInsertProduct = "INSERT INTO products (uid, title, description, img, user_uid) 
            VALUES('$uid', '$title', '$description', '$img', '$useruid')";

            try {
                if($db->simpleQuery($queryInsertProduct)) {
                    return $this->respond([
                        "error" => false,
                        "code" => 200,
                        "message" => "Successfully create a product",
                    ], 200);
                } else {
                    return $this->respond([
                        "error" => false,
                        "code" => 200,
                        "message" => "Successfully create a product",
                    ], 200);
                }
            } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
                return $this->respond([
                    "error" => true,
                    "code" => 500,
                    "message" => $e->getMessage(),
                ], 500);
            }
        }
    }


    public function initDatatablesProducts() {
        $db = Database::connect();
        $request = Services::request();
        
        $columns = [
			0 => "no",
            1 => "title",
			2 => "description",
            3 => "img",
            4 => "uploadby",
            5 => "edit",
            6 => "delete"
 		];

        $order = $columns[$request->getPost('order')[0]["column"]];
        $dir   = $request->getPost('order')[0]["dir"];

        $limit  = $request->getPost("length");
        $offset = $request->getPost("start");

        $draw 	= $request->getPost("draw");
		$search = $request->getPost("search")["value"];

        $queryProducts = $db->query("SELECT p.*, u.username FROM products p 
        INNER JOIN users u ON u.uid = p.user_uid 
        ORDER BY p.title $dir LIMIT $offset, $limit");

        if(!empty($search)) {
            $queryProducts = $db->query("SELECT p.*, u.username FROM products p 
            INNER JOIN users u ON u.uid = p.user_uid 
            WHERE p.title LIKE '%$search%' LIMIT $offset, $limit");
        } 

        $queryTotalFilteredProducts = $db->query("SELECT p.*, u.username FROM products p 
        INNER JOIN users u ON u.uid = p.user_uid");

        $products = $queryProducts->getResult();
        $productsFiltered = $queryTotalFilteredProducts->getResult();
        $totalProducts = (int) count($products);
        $totalFilteredProducts = (int) count($productsFiltered);
        $data = [];

        $i = 1;
        foreach ($products as $key => $val) {
            $image = base_url() . $val->img;
            $nestedData['no'] = $i++;
            $nestedData['title'] = $val->title;
            $nestedData['description'] = $val->description;
            $nestedData['img'] = "<img src=$image class='img-fluid'/>";
            $nestedData['uploadby'] = $val->username;
            $nestedData['edit'] = "<button type='button' class='btn btn-info'><i class='fa-solid fa-pen-to-square'></i></button>";
            $nestedData['delete'] = "<button type='button' onclick=deleteProduct('$val->uid') class='btn btn-danger'><i class='fa-solid fa-trash'></i></button>";
            $data[] = $nestedData;
        }

        echo json_encode([
			"draw" => $draw,
			"recordsTotal" => $totalProducts,
			"recordsFiltered" => $totalFilteredProducts,
			"data" => $data
		]);
    }

    public function initTotalProducts() {
        $db = Database::connect();
        try {
            $result = $db->query("SELECT * FROM products");
            $total = count($result->getResult());

            $this->respond([
                "error" => false,
                "code" => 200,
                "message" => "Successfully fetch total products",
                "data" => $total
            ], 200);
        }  catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
