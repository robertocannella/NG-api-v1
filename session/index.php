<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

try {

    $db = new Database(
        $_ENV["SESSION_DB_HOST"],
        $_ENV["SESSION_DB_NAME"],
        $_ENV["SESSION_DB_USER"],
        $_ENV["SESSION_DB_PASS"]);

    $conn = $db->getConnection();

}catch (PDOException $e){
    $error = $e->getMessage();
}


if (isset($conn)){
    echo "Connection successful";
}elseif (isset($error)){
    echo $error;
} else{
    echo "Unknown Error";
}
