<?php

use Cannella\Sessions\Autologin;

require __DIR__ . '/../bootstrap.php';

// create database object
$database = new Database(
    $_ENV["REM_DB_HOST"],
    $_ENV["REM_DB_NAME"],
    $_ENV["REM_DB_USER"],
    $_ENV["REM_DB_PASS"]
);

$userGateway = new SessionUserGateway($database);
$autologin   = new Autologin($database->getConnection(),'9','/rem');

if (isset($_COOKIE['rc_auth'])){

    $autologin->checkCredentials();

}
else{
    if (isset($_SESSION['authenticated']) || isset($_SESSION['rc_auth'])) {

    } else {
        $autologin->checkCredentials();
        header("Location: login.php");
    }
}

