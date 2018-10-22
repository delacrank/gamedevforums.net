<?php
    if(!empty($_GET['mess'])) {
        $mess = filter_input(INPUT_GET, 'mess', FILTER_SANITIZE_STRING);
    } else {
        $mess = '';
    }

    if(!isset($_SESSION['username'])) {
        header("location: index.php");
        exit();
    } else {
        $_SESSION = array();
        session_destroy();
        setcookie(session_name(), '', time()-300, '/', '', 0);
    }
    
    echo "<p>{$mess}<p>";
    echo '<h3>You are now logged out.</h3>';
    echo "<p><a href='index.php?page=home'>Back to home</a></p>";
    echo "<p><a href='index.php?page=login'>Back to login</a></p>";
?>