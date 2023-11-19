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


if (isset($_POST['login'])){

    $username = trim($_POST['username']);
    $pwd = trim($_POST['pwd']);

    $stored = $userGateway->checkPasswordHash($pwd, $username);
    if (!$stored){
        $error = "Login failed. Check credentials";
    }
    else {

        session_regenerate_id(true); // Regenerate the session ID to prevent session fixation attacks
        $_SESSION['uname'] = $username;
        $_SESSION['authenticated'] = true;

        // Autologin single use
        if (isset($_POST['remember'])){
            if (!empty($autologin)) {
                $autologin->persistentLogin();
            }
        }
        header('Location: restricted.php',);
        exit;

    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
</head>

<body>
<h1>Persistent Login</h1>
<?php
    if (isset($error)) {
        echo "<p>Error: " .  htmlspecialchars($error) . "</p>";
    };
?>
<form action="<?= $_SERVER['PHP_SELF']?>" method="post"  >
    <p>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
    </p>
    <p>
        <label for="pwd">Password</label>
        <input type="password" name="pwd" id="pwd">
    </p>
    <p>
        <input type="checkbox" name="remember" id="remember" value="remember">
        <label for="remember">Remember me</label>
    </p>
    <p>
        <input type="submit" name="login" value="Log In">
    </p>

</form>
</body>
</html>