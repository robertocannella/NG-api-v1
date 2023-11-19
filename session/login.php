<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';



if (isset($_POST['login'])){
    $username = trim($_POST['username']);
    $pwd = trim($_POST['pwd']);

    if (!empty($conn)) {
        $stmt = $conn->prepare("SELECT pwd FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $stored = $stmt->fetchColumn();

        if (!$stored) {
            $error = "Login failed. Check credentials";
        } else {
            if (password_verify($pwd, $stored)) {
                session_regenerate_id(true);
//        $_SESSION['username'] = $username;
                $_SESSION['uname'] = $username;
                $_SESSION['authenticated'] = true;
                if (isset($_POST['remember'])) {
                    //$autologin = new Autologin($conn); // initialized in boostrap.php
                    if (!empty($autologin)) {
                        $autologin->persistentLogin();
                    }
                }
                header('Location: restricted1.php');
                exit;

            }
        }
    }
    $error = "Login failed. Check credentials";

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