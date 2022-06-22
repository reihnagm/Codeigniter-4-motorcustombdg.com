<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Database;
use Config\Services;

use App\Controllers\Base\BaseController;

class AuthController extends BaseController {
    use ResponseTrait;

    public function postLogin() {
        $db = Database::connect();
        $req = Services::request();
        $session = Services::session();
        
        $body = json_decode($req->getBody());
        
        $email = $body->email;
        $password = $body->password;

        $query = "SELECT username, password FROM users WHERE email = '$email'";

        try {
            $result = $db->query($query);

            $passwordDb = $result->getResult();

            if(empty($passwordDb)) {
                $data["error"] = true;
                $data["code"] = 500;
                $data["message"] = "Account is not exist!";
                return $this->respond([
                    "error" => $data["error"],
                    "code" => $data["code"],
                    "message" => $data["message"]
                ], 500);
            } else {    
                $exist = password_verify($password, $passwordDb[0]->password);
                if($exist) {
                    $data["error"] = false;
                    $data["code"] = 200;
                    $data["message"] = "Successfully Login!";
                    $session->set([
                        "username" => $passwordDb[0]->username,
                        "email" => $email,
                        "authenticated" => true
                    ]);
                    return $this->respond([
                        "error" => $data["error"],
                        "code" => $data["code"],
                        "message" => $data["message"]
                    ], 200);
                } else {
                    $data["error"] = true;
                    $data["code"] = 500;
                    $data["message"] = "Account is not match!";
                    return $this->respond([
                        "error" => $data["error"],
                        "code" => $data["code"],
                        "message" => $data["message"]
                    ], 500);
                }
            }
        } catch(\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this->respond([
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function postRegister() {
        $db = Database::connect();
        $req = Services::request();
        $session = Services::session();
        
        $body = json_decode($req->getBody());

        $uid = uuidv4();
        $username = $body->username;
        $email = $body->email;
        $passwordBody = $body->password;

        $options = ['cost' => 12]; 
        $password = password_hash($passwordBody, PASSWORD_BCRYPT, $options);

        $query = "INSERT INTO users (uid, username, email, password) VALUES('$uid', '$username', '$email', '$password')";

        try {
            if (!$db->simpleQuery($query)) {
                $data["error"] = true;
                $data["code"] = 500;
                $data["message"] = $db->error();
                return $this->respond([
                    "error" => $data["error"],
                    "code" => $data["code"],
                    "message" => $data["message"],
                ], 500);
            } else {
                $data["error"] = false;
                $data["code"] = 200;
                $data["message"] = "Succesfully Register";
                $session->set([
                    "username" => $username,
                    "email" => $email,
                    "authenticated" => true
                ]);
                return $this->respond([
                    "error" => $data["error"],
                    "code" => $data["code"],
                    "message" => $data["message"],
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

    public function logout() {
        $session = Services::session();
        $session->remove('username');
        $session->remove('email');
        $session->remove('authenticated');
        return redirect()->to(base_url());
    }

    public function index() { 
        return view("auth/index");
    }
}