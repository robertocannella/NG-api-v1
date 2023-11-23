<!DOCTYPE html>
<html>
<head>
    <title>Your Website Title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./css/styles.css">
    <!-- Include JavaScript File -->

    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
<header>
    <nav class="navbar" style="background-color: #7E57C2;">
        <div class="container-fluid" style="text-align: center">
            <div class="d-md-none "><h1  style="color: white;" >Sessions</></div>
            <div class="d-none Dd-md-block"><h1  style="color: white;" >Remember me sessions. </h1></div>
            <?php
                if (isset($_SESSION['uname'])):
                    $user_name = ucfirst(htmlspecialchars($_SESSION['uname']));
                else:
                    $user_name = '<a href="login.php">Login</a>';
                endif; ?>
                <span style="font-size: larger"><?= $user_name ?> </span>
        </div>

    </nav>
</header>

