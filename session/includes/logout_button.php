<form action="logout.php" method="post">
    <input type="submit" name="logout" value="Log Out">
    <input type="hidden" name="return_to" value="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">

<!--    --><?php //$_SERVER['return_to'] = $_SERVER['PHP_SELF']; ?>
</form>