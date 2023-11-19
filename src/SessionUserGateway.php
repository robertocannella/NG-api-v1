<?php

class SessionUserGateway{


    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    // SESSIONS SCHEMA
    public function addSessionUser(array $user): bool
    {
        $sql = "INSERT INTO users (user_key, username, pwd) 
                VALUES (:key, :username, :pwd)";

        try {
            // Generate a random 8-character user key
            $user_key = $this->generate12CharId();

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':key', $user_key);
            $stmt->bindParam(':username', $user['username']);
            $stmt->bindValue(':pwd', password_hash($user['pwd'], PASSWORD_DEFAULT));

            return $stmt->execute();  // Returns true on success

        } catch (\PDOException $e) {
            // Handle specific error codes or rethrow the exception
            if (str_starts_with($e->getCode(), '23')) {
                // Handle specific error code (like unique constraint violation)
            }
            throw $e;
        }
    }
    public function checkPasswordHash(string $provided, string $username):bool
    {
        $sql = "SELECT pwd FROM users WHERE username = :username";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $stored = $stmt->fetchColumn();

        return password_verify($provided, $stored);
    }
    // API SCHEMA
    public function getByAPIKey(string $key): array |false
    {

        $sql = "SELECT * 
                FROM user 
                WHERE api_key = :api_key";

        $stmt =  $this->conn->prepare($sql);

        $stmt->bindValue(':api_key',$key);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function getByUsername (string $username): array | false
    {
        $sql = "SELECT *
                FROM users
                WHERE username = :username";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue('username',$username);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
    public function getById(int $id): array | false
    {
        $sql = "SELECT * 
                FROM user
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue('id',$id,PDO::PARAM_INT);

        $stmt->execute();

        return $user = $stmt->fetch(PDO::FETCH_ASSOC);

    }
    private function generate12CharId():string {
        // Generate 6 bytes of random data
        $bytes = openssl_random_pseudo_bytes(6);
        // Convert the bytes to their hexadecimal representation
        $id = bin2hex($bytes);
        // Ensure the ID is exactly 12 characters long
        return substr($id, 0, 12);
    }
}