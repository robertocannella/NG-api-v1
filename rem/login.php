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
        $_SESSION['user_key'] = $userGateway->getUserId($username);
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

<?php include './includes/header.php'; ?>
<?php
    if (isset($error)) {
        echo "<p>Error: " .  htmlspecialchars($error) . "</p>";
    };
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-6 mx-auto">
            <div class="p-4 shadow">
            <h2 class="mt-4 mb-4">Login</h2>
            <form action="<?= $_SERVER['PHP_SELF']?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" id="username">
                </div>
                <div class="mb-3">
                    <label for="pwd" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="pwd" id="pwd">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" value="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <button type="submit" class="btn btn-primary primary-button" name="login">Log In</button>
                    <a href="register.php" name="register">Register</a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row">
        <h2>About Remember Me Sessions</h2>

    </div>
    <div class="row">
        <p>
            &nbsp;This site offers a robust template for managing user sessions through a database, ensuring seamless and secure user experiences.
            Utilizing MySQL for persistent session storage, it reliably maintains user credentials across visits.
            Explore the implementation on my <a href="https://github.com/robertocannella/NG-api-v1/tree/main/rem">GitHub</a> repository
        </p>
        <p>
            &nbsp;This mini framework takes session management to the next level by extending PHP's native session handler interface,
            providing a more flexible way to handle user sessions. It incorporates well-defined Gateways as control mechanisms to
            interact seamlessly with the database, ensuring efficient and secure data handling. This approach not only enhances session
            persistence but also optimizes the overall user experience by leveraging the power of PHP's backend capabilities.
            Dive into the <a href="https://github.com/robertocannella/NG-api-v1/tree/main/rem">GitHub</a> repository to see how our framework can streamline your session management tasks
        </p>
    </div>
</div>
</body>
</html>