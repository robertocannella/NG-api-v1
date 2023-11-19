<?php

require __DIR__ . '/../bootstrap.php';


if (isset($_SESSION['authenticated']) || isset($_SESSION['rc_auth']))
{
    error_log("User has a valid session");

} elseif (isset($_COOKIE['rc_auth']))
{
    error_log("User has an auth cookie");
    if (!empty($autologin)) {
        $autologin->checkCredentials();
    }
}else
{
    error_log("No Session or Cookie stored");
    if (!empty($autologin)) {
        $autologin->checkCredentials();
    }
    header("Location: login.php");
}
