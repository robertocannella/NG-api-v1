<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

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
            $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            if ($stmt->fetchColumn() != 0){
                $errors['failed'] = "$username is already registered. Choose another username.";
            }else{
                try {
                    // Generate a random 8-character user key
                    $user_key = hash('crc32', microtime(true) . mt_rand() . $username );
                    $sql = 'INSERT INTO users (user_key, username, pwd) VALUES (:key, :username, :pwd)';

                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':key', $user_key);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindValue('pwd', password_hash($pwd, PASSWORD_DEFAULT));
                    $stmt->execute();
                }catch (\PDOException $e){
                    if (str_starts_with($e->getCode(), '23')){
                        $user_key = hash('crc32', microtime(true) . mt_rand() . $username);
                        if (!$stmt->execute()){
                            throw $e;
                        }
                    }
                }
                if ($stmt->rowCount()){
                    header("Location: login.php");
                    exit;
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <title>Create Account</title>
</head>
<body>
<h1>Create Account</h1>
<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <p>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?= isset($username) ? htmlentities($username) : ''; ?>">
        <?php if (isset($errors['username'])): ?>
            <span class="error"><?= htmlentities($errors['username']); ?></span>
        <?php elseif (isset($errors['failed'])): ?>
            <span class="error"><?= htmlentities($errors['failed']); ?></span>
        <?php endif; ?>
    </p>

    <p>
        <label for="pwd">Password:</label>
        <input type="password" name="pwd" id="pwd">
        <?php if (isset($errors['pwd'])): ?>
            <span class="error"><?= htmlentities($errors['pwd']); ?></span>
        <?php endif; ?>
    </p>
    <p>
        <label for="confirm">Password:</label>
        <input type="password" name="confirm" id="confirm">
        <?php if (isset($errors['nomatch'])): ?>
            <span class="error"><?= htmlentities($errors['nomatch']); ?></span>
        <?php endif; ?>
    </p>

    <p><input type="submit" name="register" value="Create Account"></p>
</form>

</body>
</html>