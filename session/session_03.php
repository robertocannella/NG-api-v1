<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

session_start();

if(isset($_POST['logout'])){
    $_SESSION = [];
    $params = session_get_cookie_params();
    setcookie(session_name(), "", time() - 86400 /* 24hours */,
            $params['path'],
            $params['domain'],
            $params['httponly']);
    session_destroy();
    header('location: session.php');
    exit();

}

?>
<!doctype html>
<html>
<head>

    <meta charset="utf-8">
    <title>Session Test</title>
</head>
<body>
<p>Hello <?php
    if (isset($_SESSION['first_name'])){
        echo 'again, ' . $_SESSION['first_name'];
    } else {
        echo ', stranger';
    }
    ?>.</p>
<form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
    <p>
        <input type="submit" name="logout" value="Log Out">
    </p>
</form>
</body>
</html>
