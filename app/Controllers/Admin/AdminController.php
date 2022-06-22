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
      return view('admin/index');
    }

    public function products() {
        return view('admin/products/index');
    }

    public function create() {
        return view('admin/products/create');
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

            $img = base_url().'/public/web/'.$filename;

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
        $limit = $request->getPost("length");
        $offset = $request->getPost("start");
        $queryProducts = $db->query("SELECT * FROM products LIMIT $offset, $limit");
        $products = $queryProducts->getResult();
        $total = (int) count($products);
        $data = [];
        
        // foreach ($products as $key => $value) {
        //     $nestedData['NO'] = $i++;
        //     $nestedData['TITLE'] = $value->domain;
        //     $nestedData['IMG'] = $value->group;
        //     $nestedData['DESCRIPTION']  = $value->msisdn;
        //     $nestedData['AUTHOR']  = $value->hit;
        //     $data[] = $nestedData;
        // }
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
