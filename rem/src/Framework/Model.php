<?php

declare(strict_types=1);

namespace Framework;

use App\Database;
use \PDO;

abstract class Model{
    protected PDO $conn;

    protected string|null $table = null;
    protected array $errors = [];

    protected function validate (array $data): void {}
    protected function addError (string $field, string $message):void{

        $this->errors[$field] = $message;

    }
    public function getErrors (): array
    {
        return $this->errors;
    }

    public function __construct(private readonly Database $database)
    {
        $this->conn = $this->database->getConnection();
    }
    protected function getTable(): string{

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
    public function getInsertId (): string|null {

        if ($this->conn->lastInsertId() > 0 ){
            return $this->conn->lastInsertId();
        }
        return null;

    }
    public function find(string $id): bool|array
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function insert (array $data): string|bool|int
    {
        $this->validate($data);

        if (! empty($this->errors) ) {
            return false;
        }
        $columns = implode(",", array_keys($data));
        $placeholders = implode(",", array_fill(0, count($data), "?"));

        $sql = "INSERT INTO {$this->getTable()} ($columns) VALUES ($placeholders)";

        $stmt = $this->conn->prepare($sql);

        try {

            $i = 1;
            foreach ($data as $datum) {
                $type = match (gettype($datum)) {
                    "boolean" => PDO::PARAM_BOOL,
                    "integer" => PDO::PARAM_INT,
                    "NULL" => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR
                };

                $stmt->bindValue($i++, $datum, $type);

            }

            $stmt->execute();
            return $this->getInsertId();

        } catch (\PDOException $e) {

            if ($e->getCode() === "23000") {
                $this->addError("duplicate", "A record with that ID exists.");
                return 0;
            }else{
                throw $e;
            }
        }


    }
 }
