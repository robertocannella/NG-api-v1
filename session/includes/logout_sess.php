<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';


function logout_sess($autologin): void
{

    $autologin->logout();
    $_SESSION =[];
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 86400, $params['path'], $params['domain'], $params['httponly'], $params['secure']);
    session_destroy();
    header('Location: login.php');

}