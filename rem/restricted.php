<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/authenticate.php';

$username = $_SESSION['uname'];

?>
<html>
<head>
    <title>Restricted 1</title>
</head>
<body>
<h1>Restricted</h1>
Username: <?php echo htmlspecialchars($username ?? ' no username') ?>
<?php include_once 'includes/logout_button.php' ?>
</body>
</html>
