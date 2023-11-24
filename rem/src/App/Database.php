<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database {

    private ?PDO $pdo = null;

    public function __construct(
        private readonly string $host,
        private readonly string $name,
        private readonly string $user,
        private readonly string $pass)
    {
    }
    public function getConnection () : PDO
    {

        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

        if ( $this->pdo === null ) {
            $this->pdo =  new PDO($dsn, $this->user,$this->pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions
                PDO::ATTR_STRINGIFY_FETCHES => false, // Keep numeric values numeric
                PDO::ATTR_EMULATE_PREPARES => false // Maria DB/MYSQL support this so it can be disabled
            ]);
        }

        return $this->pdo;
    }
}
