<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';


// initialize session
session_start();

if (isset($_POST['first_name'])){
    if (!empty($_POST['first_name'])){
        $_SESSION['first_name'] = htmlentities($_POST['first_name']);
    }else{
        $_SESSION['first_name'] = "Roberto";
    }

}


?>
<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>Session Test</title>
</head>
<body>
<p>Hello, <?php
    if (isset($_SESSION['first_name'])){
        echo $_SESSION['first_name'];
    }else {
        echo 'stranger';
    }
    ?></p>
<p><a href="session_03.php">Got to page 3</a></p>
</body>
</html>

