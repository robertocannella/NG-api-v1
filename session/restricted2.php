<?php
require_once './includes/authenticate.php';

if(isset($_POST['choose'])){
    $_SESSION['color'] = $_POST['color'];
}

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
</head>

<body>
<h1>Restricted Page Number 2</h1>


<?php include './includes/logout_button.php';

if (isset($_SESSION['uname'])): ?>
    <p> Still here, <?= htmlentities($_SESSION['uname']); ?><p>
<?php endif; ?>


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
</body>
<p><a href="restricted1.php">Go to page 1</a></p>
<p><a href="sensitive.php">Change Account Details</a></p>
</html>
