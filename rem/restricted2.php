<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/authenticate.php';


$username =  $_SESSION['uname'] ?? null;

if(isset($_POST['choose'])){
    $_SESSION['color'] = $session_color =  $_POST['color'];
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <title>Restricted Area 2</title>
</head>
<h1 style="color: <?= htmlspecialchars($_SESSION['color'] ?? '') ?>"><?=  htmlspecialchars(ucfirst($_SESSION['color'] ?? ''))  ?> Session Area 2</h1>



Username: <?php echo htmlspecialchars($username ?? ' no username') ?>
<?php include_once 'includes/logout_button.php' ?>


<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <label for="color">Choose a color:</label>
    <select name="color" id="color">
        <option value="">Choose one</option>
        <option value="blue"
            <?php
            if(isset($_SESSION['color']) && $_SESSION['color'] == 'blue'){
                echo 'selected' ;
            }
            ?>
        >Blue</option>
        <option value="red"
            <?php
            if(isset($_SESSION['color']) && $_SESSION['color'] == 'red'){
                echo 'selected';
            }
            ?>
        >Red</option>
        <option value="green"
            <?php
            if(isset($_SESSION['color']) && $_SESSION['color'] == 'green'){
                echo 'selected';
            }
            ?>
        >Green</option>
        <!-- Add more options as needed -->
        ?>
        ></option>
    </select>
    <input type="submit" name="choose" value="Choose">
</form>
<p><a href="restricted.php">Go to page 1</a></p>
<p><a href="sensitive.php">Change Account Details</a></p>
</body>
</html>
