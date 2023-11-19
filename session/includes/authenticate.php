<?php

require __DIR__ . '/../bootstrap.php';


if (isset($_COOKIE['rc_auth'])){

    if (!empty($autologin)) {
        $autologin->checkCredentials();
    }

}else{
    if (isset($_SESSION['authenticated']) || isset($_SESSION['rc_auth'])) {

    } else {
        if (!empty($autologin)) {
            $autologin->checkCredentials();
        }
        header("Location: login.php");
    }
}
