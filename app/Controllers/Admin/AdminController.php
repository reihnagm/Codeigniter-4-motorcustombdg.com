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
        }
        return $this->respond([
            "error" => false,
            "code" => 200,
            "message" => "Successfully fetch files",
            "data" => $data
        ], 200);
    }

    public function productsDelete($slug) {
        $db = Database::connect();
        try {
            $db->transStart();
            $queryProducts = $db->query("SELECT p.uid, p.title, p.description, GROUP_CONCAT(pf.url) AS files FROM products p 
            INNER JOIN product_files pf ON p.uid = pf.product_uid 
            WHERE p.slug = '$slug'
            GROUP BY p.uid");
            $products = $queryProducts->getResult();
            $productUid = $products[0]->uid;
            foreach(explode(',',$products[0]->files) as $key => $val) {
                if(file_exists(FCPATH . $val)) {
                    unlink(FCPATH . $val);
                }
                $db->query("DELETE FROM product_files WHERE product_uid = '$productUid'");
            }
            $db->query("DELETE FROM products WHERE slug = '$slug'");

            $db->transComplete();       
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

    public function productsEdit($slug) {
        $db = Database::connect();
        try {
            $queryProducts = $db->query("SELECT p.title, p.description, GROUP_CONCAT(pf.uid) AS pfid, GROUP_CONCAT(pf.url) AS files, 
            GROUP_CONCAT(pt.name) AS types 
            FROM products p 
            LEFT JOIN product_files pf 
            ON p.uid = pf.product_uid 
            LEFT JOIN product_types pt 
            ON pf.type = pt.id 
            WHERE p.slug = '$slug'
            GROUP BY p.uid");
            $products = $queryProducts->getResult();

            $data = [];
        
            foreach($products as $key => $value) {
                $nestedData["title"] = $value->title;
                $nestedData["description"] = $value->description;
        
                $files = [];
                foreach (explode(',', $value->files) as $key => $val) {
                    $files[] = [
                        "uid" => explode(',', $value->pfid)[$key],
                        "url" => $val,
                        "type" => explode(',', $value->types)[$key]
                    ];
                    $nestedData["files"] = $files;
                }

                $data[] = $nestedData; 
            }
         
            return $this->respond([
                "error" => false,
                "code" => 200,
                "message" => "Successfully fetch edit product",
                "data" => $data
            ], 200);
        } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function filesDelete($slug) {
        $db = Database::connect();
        $request = Services::request();
        $filesRemove = json_decode($request->getPost("filesRemove"));
        try {
            $db->transStart();
            foreach ($filesRemove as $key => $val) {
                if(file_exists(FCPATH . $val->url)) {
                    unlink(FCPATH . $val->url);
                }
                $uid = $val->uid;
                $db->query("DELETE FROM product_files WHERE uid = '$uid'");
            }
            $db->transComplete();
            return $this->respond([
                "error" => false,
                "code" => 200,
                "message" => "Successfully files delete",
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

        $productUid = uuidv4();
        $title = $request->getPost("title");
        $description = $request->getPost("description");
        $filesCount = (int) $request->getPost("filesCount");
        $useruid = $session->get("useruid");

        $slug = url_title($title, '-', true);
        
        try {
            $db->transStart();

            $db->query("INSERT INTO products (uid, title, description, user_uid, slug) 
            VALUES('$productUid', '".$db->escapeString($title)."', '".$db->escapeString($description)."', '$useruid', '$slug')");

            for ($i = 0; $i < $filesCount; $i++) {  
                $filename = $_FILES["file-".$i]["name"];
                $path = $filename;
                $type = "";
                switch (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
                    case 'png':
                        $type = 1;
                    break;
                    case 'jpg':
                        $type = 1;
                    break;
                    case 'jpeg':
                        $type = 1;
                    break;
                    case 'gif':
                        $type = 1;
                    break;
                    case 'mp4':
                        $type = 2;
                    break;
                    default:
                    break;
                }
                $url = 'public/web/'.$filename;
                move_uploaded_file($_FILES["file-".$i]["tmp_name"], $url);
                $db->query("INSERT INTO product_files (uid, url, type, product_uid) 
                VALUES('".uuidv4()."', '$url', '$type', '$productUid')");
            }       
            $db->transComplete();        
            return $this->respond([
                "error" => false,
                "code" => 200,
                "message" => "Successfully create a product",
            ], 200);
        } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function update($slug) {
        $db = Database::connect();
        $request = Services::request();

        $filesCount = $request->getPost("filesCount");
       
        try {
            $db->transStart();

            $queryProducts = $db->query("SELECT uid FROM products WHERE slug = '$slug'");
            $product = $queryProducts->getResult()[0];
            $productUid = $product->uid;

            for ($i = 0; $i < $filesCount; $i++) { 
                if(isset($_FILES["filesUpdate-".$i])) {
                    $uid = $request->getPost("filesUpdateUid-".$i); 
                    $filename = $_FILES["filesUpdate-".$i]["name"];
                    $path = $filename;
                    $type = "";
                    switch (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
                        case 'png':
                            $type = 1;
                        break;
                        case 'jpg':
                            $type = 1;
                        break;
                        case 'jpeg':
                            $type = 1;
                        break;
                        case 'gif':
                            $type = 1;
                        break;
                        case 'mp4':
                            $type = 2;
                        break;
                        default:
                        break;
                    }
                    $url = 'public/web/'.$filename;
                    move_uploaded_file($_FILES["filesUpdate-".$i]["tmp_name"], $url);
                    $db->query("REPLACE INTO product_files (uid, url, type, product_uid) VALUES('$uid', '$url', '$type', '$productUid')");
                }
            }
        
            $title = $request->getPost("title");
            $description = $request->getPost("description");
            
            $db->query("UPDATE products SET title ='$title', description = '$description'");

            $db->transComplete();

            return $this->respond([
                "error" => false,
                "code" => 200,
                "message" => "Successfully update a file",
            ], 200);
        } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function showFile($slug) {
        $db = Database::connect();
        try {
            $db->transStart();
            $queryProducts = $db->query("SELECT GROUP_CONCAT(pf.url) AS files, GROUP_CONCAT(pt.name) AS types 
            FROM products p 
            INNER JOIN product_files pf ON p.uid = pf.product_uid
            INNER JOIN product_types pt ON pt.id = pf.type 
            WHERE p.slug = '$slug'
            GROUP BY p.uid");
            $products = $queryProducts->getResult();

            $data = [];
      
            foreach ($products as $key => $value) {
                
                $files = [];

                foreach (explode(",", $value->files) as $key => $val) {
                    $files[] = [
                        "url" => $val,
                        "type" => explode(",", $value->types)[$key]
                    ];
                }

                $nestedData["files"] = $files;
                
                $files[] = $nestedData;
                
            }

            $data[] = $nestedData;
            
            return $this->respond([
                "error" => false,
                "code" => 200, 
                "message" => "Successfully fetch show file",
                "data" => $data[0]
            ],200); 
        } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function initDatatablesProducts() {
        $db = Database::connect();
        $request = Services::request();
        
        $columns = [
			0 => "no",
            1 => "title",
			2 => "description",
            3 => "files",
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
            $nestedData['no'] = $i++;
            $nestedData['title'] = $val->title;
            $nestedData['description'] = $val->description;
            $nestedData['files'] = "<button type='button' onclick=showFile('$val->slug') class='btn btn-success'><i class='fa-solid fa-file'></i></button>";
            $nestedData['uploadby'] = $val->username;
            $nestedData['edit'] = "<button type='button' @click=editProduct('$val->slug') class='btn btn-info'><i class='fa-solid fa-pen-to-square'></i></button>";
            $nestedData['delete'] = "<button type='button' onclick=deleteProduct('$val->slug') class='btn btn-danger'><i class='fa-solid fa-trash'></i></button>";
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
