<?php

require __DIR__ . '/../bootstrap.php';

// create database object
$database = new Database(
    $_ENV["REM_DB_HOST"],
    $_ENV["REM_DB_NAME"],
    $_ENV["REM_DB_USER"],
    $_ENV["REM_DB_PASS"]
);

$userGateway = new SessionUserGateway($database);

// Route the user based on Session/Cookie
if (isset($_SESSION['authenticated']) || isset($_SESSION['rc_auth']))
{
    error_log("User has a valid session");
    return;
} elseif (isset($_COOKIE['rc_auth']))
{
    if (!empty($autologin)) {
        error_log("User has a valid cookie");

        if (!$autologin->isStillValidAutologinSession()) {
           header("Location: login.php");
           exit;
        }

        $autologin->checkCredentials();
    }
}else {

    if (!empty($autologin)) {
        error_log("User needs to authenticate");
        $autologin->checkCredentials();
    }
    header("Location: login.php");
}

