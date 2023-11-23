<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/authenticate.php';


$username =  $_SESSION['uname'] ?? null;

?>

<?php include './includes/header.php'; ?>
<div class="container">
    <h2>Restricted</h2>
    Username: <?php echo htmlspecialchars($username ?? ' no username') ?>
    <?php include_once 'includes/logout_button.php' ?>


</div>

<?php include './includes/footer.php'; ?>

