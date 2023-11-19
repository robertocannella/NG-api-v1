<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

// create database object
$database = new Database(
    $_ENV["REM_DB_HOST"],
    $_ENV["REM_DB_NAME"],
    $_ENV["REM_DB_USER"],
    $_ENV["REM_DB_PASS"]
);

$userGateway = new SessionUserGateway($database);

if(isset($_POST['logout'])){
    if (!empty($autologin)) {
        $autologin->logout();
    }
    $_SESSION = [];
    $params = session_get_cookie_params();
    setcookie(session_name(), "", time() - 86400 /* 24hours */,
        $params['path'],
        $params['domain'],
        $params['httponly']);
    session_destroy();
    header('location: login.php');
    exit();
}

