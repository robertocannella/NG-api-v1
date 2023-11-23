<?php

namespace App\Models;
use Utils\Database;
use \PDO;

 class Product{
     private PDO $conn;
     public function __construct()
     {
         $db = new Database(
             $_ENV["REM_DB_HOST"],
             $_ENV["REM_DB_NAME"],
             $_ENV["REM_DB_USER"],
             $_ENV["REM_DB_PASS"]);

         $this->conn = $db->getConnection();

     }
     public function getData():array
    {

        $stmt = $this->conn->query("SELECT * FROM product");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 }