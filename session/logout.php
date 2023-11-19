<?php
require_once './includes/logout_sess.php'; // Path to the file where logout_sess() is defined


if (isset($_POST['logout'])) {
    if (!empty($autologin)) {
        logout_sess($autologin);
    }
}



// Optionally handle the case where the form wasn't submitted but the page was accessed directly
else {

    // Redirect to another page or show an error
    header('Location: some_other_page.php');
    exit;
}
