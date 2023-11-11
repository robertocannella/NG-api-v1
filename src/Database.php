<?php

class Database {
    public function __construct(
        private string $host,
        private string $name,
        private string $user,
        private string $pass)
    {
    }
    public function getConnection () : PDO {

        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";

        return new PDO($dsn, $this->user,$this->pass,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions
            PDO::ATTR_STRINGIFY_FETCHES => false, // Keep numeric values numeric
            PDO::ATTR_EMULATE_PREPARES => false // Maria DB/MYSQL support this so it can be disabled
        ]);
    }
}
