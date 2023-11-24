<?php

declare(strict_types=1);

namespace Framework;

use App\Database;
use \PDO;

abstract class Model{
     private PDO $conn;

     protected string|null $table = null;

     public function __construct(private readonly Database $database)
     {

         $this->conn = $this->database->getConnection();

     }
     private function getTable(): string{

         if ($this->table !== null) {

             return $this->table;
         }

         $parts = explode("\\", $this::class);

         return strtolower(array_pop($parts));
     }
     public function findAll():array
    {

        $sql = "SELECT * FROM {$this->getTable()}";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $id): bool|array
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
 }
