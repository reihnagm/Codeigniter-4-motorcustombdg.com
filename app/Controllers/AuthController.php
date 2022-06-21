<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Config\Database;

use App\Controllers\Base\BaseController;

class AuthController extends BaseController {
    use ResponseTrait;

    public function postLogin() {
        $req = Services::request();
        
        $body = json_decode($req->getBody());

        $username = $body->username;
        $email = $body->email;
        $password = $body->password;

        $data["error"] = null;

        $query = `INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password')`;
        $db = db_connect();
        $db->query($query);
        if (! $db->simpleQuery('SELECT `example_field` FROM `example_table`')) {
            $data["error"] = $db->error(); 
        }
        $data["code"] = 200;
        $data["message"] = "Succesfully Login";
        $this->respond([
            "error" => $data["error"],
            "code" => $data["code"],
            "message" => $data["message"],
        ], 200);
    }

    public function postRegister() {
        $db = Database::connect();
        $req = Services::request();

        $body = json_decode($req->getBody());

        $uid = uuidv4();
        $username = $body->username;
        $email = $body->email;
        $passwordBody = $body->password;

        $options = ['cost' => 12]; 
        $password = password_hash($passwordBody, PASSWORD_BCRYPT, $options);

        $data["error"] = null;

        $query = "INSERT INTO users (uid, username, email, password) VALUES('$uid', '$username', '$email', '$password')";
        
        if($db) {
            $db->query("SELECT * FROM users");
        } else {
            die("disconnect");
        }
        // try {
        //     die(var_dump($db->query('SELECT * FROM users')));
        // } catch(\Exception $e) {
        //     $this->respond([
        //         "error" => "",
        //         "code" => "",
        //         "message" => "",
        //     ], 500);
        // }
        // if(!$db->simpleQuery($query)) {
        //     $data["error"] = "ups";
        //     $data["code"] = 500;
        //     $data["message"] = "Failed Register";
        //     $this->respond([
        //         "error" => $data["error"],
        //         "code" => $data["code"],
        //         "message" => $data["message"],
        //     ], 500);
        // } else {
        //     $data["code"] = 200;
        //     $data["message"] = "Succesfully Register";
        //     $this->respond([
        //         "error" => $data["error"],
        //         "code" => $data["code"],
        //         "message" => $data["message"],
        //     ], 200);
        // }
    }   

    public function index() { 
        return view("auth/index");
    }
}