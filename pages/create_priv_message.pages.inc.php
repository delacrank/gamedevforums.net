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
    $to = $subject = $body = $subject = $success = $error1 = $error2 = $error3 = $error4 = '';
    if(!empty($_POST['submitted'])) {
        //if (preg_match('%^[a-z, ]+$%i', trim($_POST['to']))) {
        if (stripslashes(trim($_POST['to']))) {
//            if(strpos($_POST['to'], $_SESSION['username']) !== false) {
//                $to = FALSE;
//                $error1 = '<p>Cannot send message to yourself</p>';
//            } else {
            $to = filter_input(INPUT_POST, 'to', FILTER_SANITIZE_STRING);
            $user_names = explode(',', $to);
            foreach($user_names as &$u) {
                $u = trim($u);
                if($u == $_SESSION['username']) {
                    $to = FALSE;
                    $error4 = '<p>Cannot send message to yourself.</p>';
                }
            }
            if($user_ids = fetch_user_ids($user_names)) {
                if(count($user_ids) !== count($user_names)) {
                    $to = FALSE;
                    $error4 = '<p>Some users could not be found' . implode(', ', array_diff($user_names, array_keys($user_ids)));
                }
            } else {
                $error4 = '<p>No users match that username</p>';
                $to = FALSE;
            }
            $error1 = '';
        } else {
            $to = FALSE;   
            $error1 = '<p>Please enter a valid username.<p>';
        }
        // enter a valid email address
        if (stripslashes(trim($_POST['subject']))) {
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $error2 = '';
        } else {
            $subject = FALSE;
            $error2 = '<p>Please enter a valid subject.</p>';
        }

        if (stripslashes(trim($_POST['body']))) {
            $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $error3 = '';
        } else {
            $from = FALSE;
            $error3 = '<p>Please enter valid input for this field</p>';
        }
        
        $user_id = $_SESSION['user_id'];
    
        if( $to && $subject && $body && $user_id && $user_ids) {
            create_conversation(array_unique($user_ids), $user_id, $subject, $body);
            $success = '<p>Success your message has been sent <a href="index.php?page=inbox">Back to inbox here</a>';
            $to = $subject = $body = '';
            $user_ids = array();
        }
    }
?>
    <form class="form" action="" name="create_message" method="post" onsubmit="return create_message_form()">
        <div>
        <h1>Send Private Message</h1>
        <fieldset>
            <p>
                <label for='to'>To:</label>
                <input type='text' id='to' name='to' value='<?= $to ?>'/>
                <span id='mTo_err'><?= $error1 ?></span>
                <span><?= $error4 ?></span>
            </p>
            <p>
                <label for='subject'>Subject:</label>
                <input type='text' id='subject' name='subject' value='<?= $subject ?>'/>
                <span id='mSubject_err'><?= $error2 ?></span>
            </p>
            <p>
                <label for='body'>Message:</label>
                <textarea id='body' name='body' rows=10 cols=30><?= $body ?></textarea>
                <span id='mBody_err'><?= $error3 ?></span>
            </p>
            <input type='hidden' name='submitted' value='true'/>
        </fieldset>    
        </div>
        <input type='submit' value='send message'/>
    </form>
    <span><?= $success ?></span>
    <br /><br />

<a href="index.php?page=home">Back to home</a><br />
<a href="index.php?page=forums">To forums</a><br /><br />
<?php 
} else {
    header('Location: index.php?page=home&mess=You are not logged in');
    exit();
}
?>