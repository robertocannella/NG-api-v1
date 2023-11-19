<?php
require_once './includes/authenticate.php';

if (isset($_POST)) {

    $currentPassword = $_POST['current'] ?? '';
    $newPassword = $_POST['new'] ?? '';
    $confirmPassword = $_POST['confirm'] ?? '';

    // Validate input and check if current password is correct.
    // Make sure new passwords match and follow your password policy (length, complexity, etc.)
    // Update password in your database or authentication system.

    // Provide feedback to the user.

    $expected = ['current', 'new', 'confirm'];

    foreach ($_POST as $key => $value) {

        if (in_array($key, $expected)) {

            $$key = trim($value);

            if (empty($$key)) {
                echo $$key;
                $errors[$key] = 'This field requires a value';
            }
        }
    }

    if (!isset($errors)) {
        if (!isset($new)) {

        } elseif ($new !== $confirm) {
            $errors['nomatch'] = 'Passwords do not match';
        } elseif (!$userGateway->isValidPassword($new)) {
            $errors['weak'] = "Password too weak";
        }else{
            $user = $userGateway->getByUsername($_SESSION['uname']);

            if (!$user) {
                $errors['failed'] = "No valid user found.";
            } else {
                $errors['success'] = "Updated the user password";
                $userGateway->updateUserPassword($_SESSION['user_key'],$confirm);
            }

        }
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
</head>

<body>
<h1>Sensitive</h1>

<?php include_once 'includes/logout_button.php' ?>

<?php if (isset($_SESSION['uname'])): ?>
<p> Hi, <?= htmlentities($_SESSION['uname']); ?><p>
    <?php endif; ?>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <div>
        <label for="current">Current Password:</label>
        <input type="password" name="current" id="current" >
        <?php if (isset($errors['current'])): ?>
        <span class="error"><?= htmlentities($errors['current']); ?></span>
        <?php endif; ?>
        <?php if (isset($errors['failed'])): ?>
            <span class="error"><?= htmlentities($errors['failed']); ?></span>
        <?php endif; ?>
    </div>
    <div>
        <label for="new">New Password:</label>
        <input type="password" name="new" id="new" >
        <?php if (isset($errors['new'])): ?>
            <span class="error"><?= htmlentities($errors['new']); ?></span>
        <?php endif; ?>
    </div>
    <div>
        <label for="confirm">Confirm New Password:</label>
        <input type="password" name="confirm" id="confirm" >
        <?php if (isset($errors['confirm'])): ?>
            <span class="error"><?= htmlentities($errors['confirm']); ?></span>
        <?php endif; ?>
        <?php if (isset($errors['nomatch'])): ?>
            <span class="error"><?= htmlentities($errors['nomatch']); ?></span>
        <?php endif; ?>
    </div>
    <?php if (isset($errors['failed'])): ?>
        <span class="error"><?= htmlentities($errors['failed']); ?></span>
    <?php endif; ?>
    <?php if (isset($errors['weak'])): ?>
        <span class="error"><?= htmlentities($errors['weak']); ?></span>
    <?php endif; ?>
    <?php if (isset($errors['success'])): ?>
        <span class="error"><?= htmlentities($errors['success']); ?></span>
    <?php endif; ?>
    <div>
        <button type="submit">Change Password</button>
    </div>
</form>

<p><a href="restricted.php">Go to page 1</a></p>
<p><a href="restricted2.php">Go to page 2</a></p>
</html>

