<?php
if(!empty($_SESSION['username'])) {
    if($row = check_valid($_SESSION['username'])) {
        if($_SESSION['tokenid'] == $row['tokenid']) {
            $loggedin = 1;
        } else {
            echo "You are not logged in.";
            $loggedin = 0;
            exit();
        }
    }
} 
    
if(!empty($_SESSION['username']) && (substr($_SERVER['PHP_SELF'], -6) != 'logout') && $loggedin) {

    $username = $old_pass = $new_pass = $new_pass2 = $error1 = $error2 = $error3 = $error4 = '';
    if(!empty($_POST['submitted'])) {
        if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['username'])))) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $error1 = '';
        } else {
            $username = FALSE;
            $error1 = '<p>Username should have one uppsercase letter and at least a digit.</p>';
        }
        
        if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['old_pass'])))) {
            $old_pass = filter_input(INPUT_POST, 'old_pass', FILTER_SANITIZE_STRING);
            $error2 = '';
        } else {
            $old_pass = FALSE;
            $error2 = '<p>Must submit a password</p>';
        }
        
        if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['new_pass'])))) {
            if($_POST['old_pass'] != $_POST['new_pass']) {
                if ($_POST['new_pass'] == $_POST['new_pass2']) {
                    $new_pass = filter_input(INPUT_POST,'new_pass', FILTER_SANITIZE_STRING);
                    $error3 = $error4 = '';
                } else {
                    $new_pass = FALSE;
                    $error3 = '<p>Your passwords do not match</p>';
                }
            } else {
                $new_pass = FALSE;
                $error3 = '<p>Your new pass must be different then your old pass</p>';
            }
        } else {
            $new_pass = FALSE;
            $error4 = '<p>Password must contain at least 8 characters, a lowercase, uppercase and a number.</p>';
        }
        
        if($username && $old_pass && $new_pass) {
            if(change_password($username, $old_pass, $new_pass)) {
                $username = $old_pass = $new_pass = $new_pass2 = '';
                header('Location: index.php?page=logout&mess=Your password has been successfully changed');
            }
        }
    }
?>

<p>You can change your password here, just input the old password in the first box to check. You can change your password below just make sure that your new passwrod contains an uppercase char and a number.</p>


<form class = 'form' name='change_password' action='' method='post' onsubmit='return change_profile_password()'>
    <div>
    <h1> Change Password </h1>
    <fieldset>
    <p>
        <label for='username'>Username:</label>
        <input id='username' type='text' name='username' value='<?= $username ?>' />
        <span id='cpUsername_err'><?= $error1 ?></span>
    </p>
    <p>
        <label for='old_pass'>Password:</label>
        <input id='old_pass' type='password' name='old_pass' />
        <span id='cpOld_pass_err'><?= $error2 ?></span>
    <p>
        <label for='new_pass'>New Password:</label>
        <input id='new_pass' type='password' name='new_pass' />
        <span id='cpNew_pass_err'><?= $error3 ?></span>
    </p>
    <p>
        <label for='new_pass2'>Confirm New Password:</label>
        <input id='new_pass2' type='password' name='new_pass2' />
        <span id='cpNew_pass2_err'><?= $error4 ?></span>
    </p>
    <input type='hidden' name='submitted' value='TRUE'/>
    </fieldset>
    </div>
    <input type='submit' value='change password'/>
</form>

<br /><br />
<a href='index.php?page=home'>Home</a><br /><br />
<a href='index.php?page=forums'>Forums</a><br />
<?php
} else {
    header('Location: index.php?page=home&mess=You are not logged in');
    exit();
}
?>