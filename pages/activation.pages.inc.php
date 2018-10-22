<?php 

// Validate $_GET[‘x’] and $_GET[‘y’].
if (!empty($_GET['x'])) {
    $x = filter_input(INPUT_GET, 'x', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
} else {
    $x = '';
}

if (!empty($_GET['y'])) {
    $y = filter_input(INPUT_GET, 'y', FILTER_SANITIZE_STRING);
} else {
    $y = '';
}

// If $x and $y aren’t correct, redirect the user.
if (($x > 0) && (strlen($y) == 32)) {
    if(activate($x, $y)) {
        echo '<br><br><h3>Your account is now active. You may now log in.</h3>
        <p>Click here to go back to main page <a href="index.php">Home</a></p>';
    } else {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        // Check for a trailing slash.
        if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
            $url = substr ($url, 0, -1); // Chop off the slash.
        }
        $url .= '/index.php';
        ob_end_clean(); // Delete the buffer.
        header("Location: $url");
        exit(); 
    }
} 
?>
