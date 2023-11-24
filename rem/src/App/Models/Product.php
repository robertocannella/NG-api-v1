<?php

declare(strict_types=1);

namespace App\Models;
use Framework\Model;
use \PDO;

class Product extends Model {
   // protected ?string $table = "product";

    protected function validate (array $data): void
    {



        if (empty($data["product_id"])) {

            $this->addError("product_id", "A unique product number is required");

        }
        if (empty($data["name"]) ){

            $this->addError("name", "Name is required");
        }

        if (isset($data["product_id"] )){
            $exists = $this->existsProductId((int) $data["product_id"]);

            if ($exists){

                $this->addError("duplicate", "A record with that product ID exists");
            }

        }

    }
    public function existsProductId (int $product_id):bool|array
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE product_id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

 }
