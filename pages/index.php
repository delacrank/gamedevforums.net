<?php
 require_once('../template.php');
 ob_start();
 /* Registration **************************************/
    $rUsername = $rEmail = $rSq = '';
    $rError1 = $rError2 = $rError3 = $rError4 = $rError5 = '';
    if (!empty($_POST['rSubmitted'])) { 
        // enter a valid username
        if (preg_match ('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['rUsername'])))) {
            $rUsername = filter_input(INPUT_POST, 'rUsername', FILTER_SANITIZE_STRING);
            $rError1 = '';
        } else {
            $rUsername = FALSE;   
            $rError1 = '<p>Password must contain at least 8 characters, a lowercase, uppercase and a number.<p>';
        }
        // enter a valid email address
        if (preg_match ('/^[A-Za-z0-9._\%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', stripslashes(trim($_POST['rEmail'])))) {
            $rEmail = filter_input(INPUT_POST, 'rEmail', FILTER_SANITIZE_STRING);
            $rError2 = '';
        } else {
            $rEmail = FALSE;
            $rError2 = '<p>Please enter a valid email address</p>';
        }
        // enter a valid security answer
        if(preg_match('/^[A-Za-z ]{2,20}$/', stripslashes(trim($_POST['rSq'])))) {
            $rSq = filter_input(INPUT_POST, 'rSq', FILTER_SANITIZE_STRING);
            $rError3 = '';
        } else {
            $rSq = FALSE;
            $rError3 = '<p>Please enter your mother\'s maiden name.</p>';
        }
        // enter a valid password and confirm that password
        if (preg_match ('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['pass1'])))) {
            if ($_POST['pass1'] == $_POST['pass2']) {
                $rPass = filter_input(INPUT_POST,'pass1', FILTER_SANITIZE_STRING);
                $rError4 = $rError5 = '';
            } else {
                $rPass = FALSE;
                $rError4 = '<p>Your passwords do not match</p>';
            }
        } else {
            $rPass = FALSE;
            $rError5 = '<p>Password must contain at least 8 characters, a lowercase, uppercase and a number.</p>';
        }
        // check all fields to proceed with database entries
        if($rUsername && $rEmail && $rSq && $rPass) {
            if(register_account($rUsername, $rEmail, $rSq, $rPass)) {
                $rUsername = $rEmail = $rSq = $rPass = '';
                echo '<h1>Thank you for registering!</h1>
                <p>A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account.</p>
                <p>Click <a href="index.php?page=home">here</a> to navigate back to home page</p>';
            }  
        }
    }
    
  /* Forgot Pass **************************************/
    $fError1 = $fError2 = $fSq = $fUser = '';
    if(!empty($_POST['fSubmitted'])) {
        if(stripslashes(trim($_POST['fUsername']))) {
            $fUser = filter_input(INPUT_POST,'fUsername', FILTER_SANITIZE_STRING);
        } else {
            $fUser = FALSE;
            $fError1 = "<p>Please enter a username</p>";
        }
        
        if(stripslashes(trim($_POST['mother_name']))) {
            $fSq = filter_input(INPUT_POST,'mother_name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        } else {
            $fSq = FALSE;
            $fError2 = '<p>Please enter your mother\'s maiden name.</p>';
        }
        
        // create a captcha check, to verify user is not a bot
        /*
        $captchchk = 1;
        $privatekey = '6LezymYUAAAAAOs8lcySVjS92lCfCWe_FZqVrEL4';
        $resp = recaptcha_check_answer($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_reponse_field']);
        if(!$resp->is_valid) {
            echo '<p>The captcha code wasn\'t entered correctly.</p>';
            $captchchk = 0;
        }
        */
        
        // check to see if user, pass and captcha all valid
        // if valid create a session for username and a token
        
        if($fUser && $fSq /*&& captchck*/) {
            if(forgot_pass($fUser, $fSq)) {
                echo '<h3>Your password has been changed. You will receive the new one at your current email address. Once you log in you can change it by clicking on the change password link.</h3>';
            } else {
                echo '<p>Check your username or security question.</p>';
            }
        }
    }
    
  /* Login **************************************/
 $user = $error1 = $error2 = '';
    if(!empty($_POST['lSubmitted'])) {
        // sanitize username intialize username variable
        if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['username'])))) {
            $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $error1 = '';
        } else {
            $user = FALSE;
            $error1 = '<p>Username must contain at least 8 characters, a lowercase, uppercase and a number.</p>';
        }
        
        // sanitize password intialize username variable
        if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['pass'])))) {
            $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
            $error2 ='';
        } else {
            $pass = FALSE;
            $error2 = '<p>Password must contain at least 8 characters, a lowercase, uppercase and a number.</p>';
        }
        
        // create a captcha check, to verify user is not a bot
        /*
        $captchchk = 1;
        $privatekey = '6LezymYUAAAAAOs8lcySVjS92lCfCWe_FZqVrEL4';
        $resp = recaptcha_check_answer($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_reponse_field']);
        if(!$resp->is_valid) {
            echo '<p>The captcha code wasn\'t entered correctly.</p>';
            $captchchk = 0;
        }
        */
        
        // check to see if user, pass and captcha all valid
        // if valid create a session for username and a token
        if($user && $pass /*&& $$captchchk*/) {
            if($row = login($user, $pass)) { 
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];
                $row2 = token($_SESSION['username']);
                $_SESSION['tokenid'] = $row2['tokenid'];
                session_regenerate_id();
                header('Location: index.php?page=profile');
                exit();
            } else {
                echo '<h1>Login Failure</h1>';
                echo '<p>You failed to login, either your password or username was incorrect want to try to <a>login</a> again?</p>';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title><?= $title ?></title>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src='../JS/login.js'></script>
<link rel="stylesheet"
      type="text/css"
      href="../CSS/gamedev.css">
<link href="https://fonts.googleapis.com/css?family=Advent+Pro|Cinzel|Indie+Flower|Orbitron|Sorts+Mill+Goudy" rel="stylesheet">
</head>
<body>
<header>
    <nav id='navigation'>
        <ul>
            <li><a href='index.php?page=home'>HomePage</a></li>
            <li><a href='index.php?page=forums'>Forums</a></li>
            <li><a href='index.php?page=article_index'>Articles</a></li>
            <?php if(!empty($_SESSION['username'])) {
                echo "<li><a href='index.php?page=logout'>Logout</a></li>";
                echo "<li><a href='index.php?page=profile'>Profile</a></li>";
            } else {
                echo "<li><a onclick = 'login_modal()'>Login</a></li>"; 
            } ?>
        </ul>
    </nav>
    <nav id='mini-navigation'>
        <div id='menuToggle'>
        <input type='checkbox'/>
            <span></span>
            <span></span>
            <span></span>
            <ul id='menu'>
                <li><a href='index.php?page=home'>HomePage</a></li>
                <li><a href='index.php?page=forums'>Forums</a></li>
                <li><a href='index.php?page=article_index'>Articles</a></li>
                <?php if(!empty($_SESSION['username'])) {
                    echo "<li><a href='index.php?page=logout'>Logout</a></li>";
                    echo "<li><a href='index.php?page=profile'>Profile</a></li>";
                } else {
                    echo "<li><a onclick = 'login_modal()'>Login</a></li>"; 
                } ?>
            </ul>
        </div>
    </nav>
</header>    
    
<main>
<div id='login_modal' class='login_modal'>
<!----- Login ------------------------------------------>
    <div class='login_modal_content'>
        <ul>
            <li class ='lPanel_button' onclick='chooselPanel(1)'><a>Login</a></li>
            <li class ='lPanel_button' onclick='chooselPanel(2)'><a>Registration</a></li> 
        </ul>
        <div class='login_modal_form'>
            <form class = 'form login_form' name='login' action='' method='post' onsubmit="return login_form()">
                <div>
                    <fieldset>
                        <p>
                            <label for='username'>Username: </label> 
                            <input type='text' id='lusername' name='username' size='20' maxlength='20' value='<?= $user ?>' />
                            <span id='lUsername_err'><?= $error1 ?></span>
                        </p>
                        <p>
                            <label for='password'>Password: </label>
                            <input type='password' id='lpassword' name='pass' size='20' maxlength='20' />
                            <span id='lPassword_err'><?= $error2 ?></span>
                        </p>
                    <?php /*
                    require_once('./recaptchalib.php');
                    $publickey = '6LezymYUAAAAAGH3IcKWeuGlwe29sdxo256i2w5W';
                    echo recaptcha_get_html($publickey); */
                    ?>
                    <!-- <div class="g-recaptcha" data-sitekey="6LezymYUAAAAAGH3IcKWeuGlwe29sdxo256i2w5W"></div> -->
                        <input type='hidden' name='lSubmitted' value='TRUE' />
                        <input type='submit' name='submit' value='Login' />
                    </fieldset>
                </div>
            </form>
            <br /><br />
        </div>
    <!----- Registration ------------------------------------------>
        <div class='login_modal_form'>
            <form class = 'form login_form' name ='registration' action='' method='post' onsubmit="return registration_form()">
            <div>
                <fieldset>
                    <p>
                        <label for='username'>Username:</label> 
                        <input type='text' id='username' name='rUsername' size='20' maxlength='20' value='<?= $rUsername ?>' />
                        <span id='rUsername_err'><?= $rError1 ?></span>
                    </p>
                    <p>
                        <label for='email'>Email:</label> 
                        <input type='text' id='email' name='rEmail' size='40' maxlength='40' value='<?= $rEmail ?>' />
                        <span id='rEmail_err'><?= $rError2 ?></span>
                    </p>
                    <p>
                        <label for='sq'>What's your mothers maiden name?:</label>
                        <input type='text' id='sq' name='rSq' size='20' maxlength='20' value='<?= $rSq ?>' /> 
                        <span id='rSq_err'><?= $rError3 ?></span>
                    </p>
                    <p>
                        <label for='pass1'>Password:</label>
                        <input type='password' id='pass1' name='pass1' size='20' maxlength='20' /> 
                        <span id='rPass1_err'><?= $rError4 ?></span>
                    </p>
                    <p>
                        <label for='pass2'>Confirm Password:</label>
                        <input type='password' id='pass2' name='pass2' size='20' maxlength='20' /> 
                        <span id='rPass2_err'><?= $rError5 ?></span>
                    </p>
                <input type='hidden' name='rSubmitted' value='TRUE' />
                <input type='submit' name='submit' value='Register' />
                </fieldset>
                </div>
            </form> 
            <br /><br />
        </div>
    <!----- Forgot Pass ------------------------------------------>
        <div class='login_modal_form'>
            <form class = 'form  login_form' name='forgot_pass' action='' method='post' onsubmit='return forgot_password_form()'>
            <div>
                <fieldset>
                    <p>
                        <label for='xusername'>Username: </label> 
                        <input type='text' id='xusername' name='fUsername' size='20' maxlength='20'/>
                        <span id='fpUsername_err'><?= $error1 ?></span>
                    </p>
                    <p>
                        <label for ='mother_name'>Mother or Grandmothers Maiden Name: </label>
                        <input type='text' id='mother_name' name='mother_name' size='25' maxlength='25'/>
                        <span id='fpMother_name_err'><?= $error2 ?></span>
                    </p>
                <!-- <?php /*
                require_once('./recaptchalib.php');
                $publickey = '6LezymYUAAAAAGH3IcKWeuGlwe29sdxo256i2w5W';
                echo recaptcha_get_html($publickey); */
                ?>
                <div class="g-recaptcha" data-sitekey="6LezymYUAAAAAGH3IcKWeuGlwe29sdxo256i2w5W"></div> -->

                    <input type='hidden' name='fSubmitted' value='TRUE' />
                    <input type='submit' name='submit' value='Reset My Password' />
                </fieldset>
                </div>
            </form>
            <br /><br />
        </div>
        <a class ='lPanel_button fp_button' onclick='chooselPanel(3)'>Click here if you forgot your password</a>
    </div>
</div>
<?php
    include($include_file);
?>

<br />
</main>
    
<footer>
        <ul>
            <li><a href='index.php?page=home'>HomePage</a></li>
            <li><a href='index.php?page=forums'>Forums</a></li>
            <li><a href='index.php?page=article_index'>Articles</a></li>
            <?php if(!empty($_SESSION['username'])) {
                echo "<li><a href='index.php?page=logout'>Logout</a></li>";
                echo "<li><a href='index.php?
                page=profile'>Profile</a></li>";
            }else {
                echo "<li><a onclick = 'login_modal()'>Login</a></li>"; 
            } ?>
        </ul>
</footer>    
<script src='../JS/forgot_password.js'></script>
<script src='../JS/login.js'></script>
<script src='../JS/create_priv_message.js'></script>
<script src='../JS/registration.js'></script>
<script src='../JS/gamedev.js'></script>
<script src='../JS/reply_conversation.js'></script>
<script src='../JS/create_post.js'></script>
<script src='../JS/reply_forum_post.js'></script>
<script src='../JS/change_password.js'></script>
<script src='../JS/profile.js'></script>
</body>
</html>

    
        