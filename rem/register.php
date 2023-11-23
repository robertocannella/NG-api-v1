<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

// create database object
$database = new Database($_ENV["REM_DB_HOST"],
    $_ENV["REM_DB_NAME"],
    $_ENV["REM_DB_USER"],
    $_ENV["REM_DB_PASS"]);

$userGateway = new SessionUserGateway($database);

$errors = [];
if (isset($_POST['register'])){
    $expected = ['username', 'pwd', 'confirm'];
    foreach ($_POST as $key => $value){
        if (in_array($key, $expected)){

            $$key = trim($value);

            if (empty($$key)){
                echo $$key;
                $errors[$key] = 'This field requires a value';
            }
        }
    }
    if (!$errors){
        if ($pwd !== $confirm){
            $errors['nomatch'] = 'Passwords do not match';
        }else{
            $user = $userGateway->getByUsername($username);
            if ($user){
                $errors['failed'] = "$username is already registered. Choose another username.";
            }else{
                $userAdded = $userGateway->addSessionUser(
                        [
                            'username'=> $username,
                            'pwd'     => $pwd
                        ]
                );
                if ($userAdded){
                    header("Location: login.php");
                    exit;
                }
            }

        }
    }
}
?>
<?php include './includes/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-md-6 mx-auto">
            <div class="p-4 shadow">
                <h2 class="mt-4 mb-4">Create Account</h2>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?= isset($username) ? htmlentities($username) : ''; ?>">
                        <?php if (isset($errors['username'])): ?>
                            <span class="error"><?= htmlentities($errors['username']); ?></span>
                        <?php elseif (isset($errors['failed'])): ?>
                            <span class="error"><?= htmlentities($errors['failed']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Password</label>
                        <input class="form-control" type="password" name="pwd" id="pwd">
                        <?php if (isset($errors['pwd'])): ?>
                            <span class="error"><?= htmlentities($errors['pwd']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Password</label>
                        <input class="form-control" type="password" name="confirm" id="confirm">
                        <?php if (isset($errors['nomatch'])): ?>
                            <span class="error"><?= htmlentities($errors['nomatch']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-primary primary-button" name="register">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>