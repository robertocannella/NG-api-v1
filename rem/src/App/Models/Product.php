<?php

namespace App\Models;
use App\Database;
use \PDO;

class Product{
     private PDO $conn;
     public function __construct(private Database $database)
     {
         $this->database = new Database();

         $this->conn = $this->database->getConnection();

     }
     public function getData():array
    {

        $stmt = $this->conn->query("SELECT * FROM product");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 }