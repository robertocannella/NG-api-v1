<?php

require dirname(__DIR__) . "/rem/vendor/autoload.php";

use Cannella\Sessions\Autologin;
use Cannella\Sessions\PersistentSessionHandler;
use Utils\Database;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

//// Content-Security-Policy: Defines which content sources are allowed. Helps prevent XSS attacks and data injection.
//header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'; style-src 'self'; frame-ancestors 'none';");
//// X-Content-Type-Options: Prevents MIME type sniffing. Ensures that browsers follow the MIME types specified.
//header("X-Content-Type-Options: nosniff");
//// X-Frame-Options: Protects against clickjacking attacks by preventing your content from being embedded into other sites.
//header("X-Frame-Options: DENY");
//// X-XSS-Protection: Enables built-in XSS protections in older browsers.
//header("X-XSS-Protection: 1; mode=block");
//// Referrer-Policy: Controls how much referrer information is passed along with requests.
//header("Referrer-Policy: no-referrer");
//// Permissions-Policy (formerly Feature-Policy): Allows you to control which browser features and APIs can be used within your app.
//header("Permissions-Policy: geolocation='self'");
// Content type header (adjust based on your application's output)
header("Content-Type: text/html; charset=UTF-8");


try {
    $db = new Database(
        $_ENV["REM_DB_HOST"],
        $_ENV["REM_DB_NAME"],
        $_ENV["REM_DB_USER"],
        $_ENV["REM_DB_PASS"]);
}catch (PDOException $e){
    $error = $e->getMessage();
    throw $e;
    exit();
}

$conn        = $db->getConnection();
$handler     = new PersistentSessionHandler($conn);
$autologin   = new Autologin($conn,'9','/rem', 'nuvolagraph.com');
session_set_save_handler($handler);
session_start();
$_SESSION['active'] = time();
