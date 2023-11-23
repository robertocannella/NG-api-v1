<?php

namespace Framework\Models;
use Database;

use \PDO;

 class Product{
     public function __construct(private readonly Database $db)
     {
     }
     public function getData():array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT * FROM product");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 }