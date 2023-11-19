<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Cannella\Sessions\Autologin;
use Cannella\Sessions\MysqlSessionHandler;
use Cannella\Sessions\PersistentProperties;
use Cannella\Sessions\PersistentSessionHandler;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

try {
    $db = new Database(
        $_ENV["SESSION_DB_HOST"],
        $_ENV["SESSION_DB_NAME"],
        $_ENV["SESSION_DB_USER"],
        $_ENV["SESSION_DB_PASS"]);
}catch (PDOException $e){
    $error = $e->getMessage();
    throw $e;
    exit();
}
$conn = $db->getConnection();
$handler = new PersistentSessionHandler($conn);
session_set_save_handler($handler);
$autologin = new Autologin($conn);
session_start();
$_SESSION['active'] = time();
