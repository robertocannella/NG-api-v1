<?php
require_once './includes/authenticate.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
</head>

<body>
<h1>Restricted Page</h1>
<?php include './includes/logout_button.php'; ?>

<?php if (isset($_SESSION['uname'])): ?>
    <p> Hi, <?= htmlentities($_SESSION['uname']); ?><p>
<?php endif; ?>

<p><a href="restricted2.php">Go to page 2</a></p>
<p><a href="sensitive.php">Change Account Details</a></p>
</html>
