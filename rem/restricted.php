<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/authenticate.php';


$username =  $_SESSION['uname'] ?? null;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <title>Restricted</title>
</head>
<h1>Restricted</h1>
Username: <?php echo htmlspecialchars($username ?? ' no username') ?>
<?php include_once 'includes/logout_button.php' ?>

<p><a href="restricted2.php">Go to page 2</a></p>
<p><a href="sensitive.php">Change Account Details</a></p>
</body>
</html>
