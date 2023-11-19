<?php
require_once './includes/authenticate.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate input and check if current password is correct.
    // Make sure new passwords match and follow your password policy (length, complexity, etc.)
    // Update password in your database or authentication system.

    // Provide feedback to the user.
}


?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
</head>

<body>
<h1>Sensitive Page</h1>
<h2>Validate user when updating.</h2>

<?php include './includes/logout_button.php'; ?>
<?php if (isset($_SESSION['uname'])): ?>
    <p> Hi, <?= htmlentities($_SESSION['uname']); ?><p>
<?php endif; ?>

<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <div>
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" required>
    </div>
    <div>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
    </div>
    <div>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
    </div>
    <div>
        <button type="submit">Change Password</button>
    </div>
</form>

<p><a href="restricted1.php">Go to page 1</a></p>
<p><a href="restricted2.php">Go to page 2</a></p>
</html>

