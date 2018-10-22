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
    $mess = $error1 = $error2 = '';
    $user_id = $_SESSION['user_id'];

    if(isset($_GET['conversation_id'])) {
        $conversation_id = filter_input(INPUT_GET, 'conversation_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    $valid_conversation = $conversation_id && validate_conversation_id($conversation_id, $user_id);

    if($valid_conversation === false) {
        $error1 = '<p>Conversation Id not valid </p>';
    }

    if(isset($_POST['submitted'])) {
        if(stripslashes(trim($_POST['message']))) {
            $mess = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        } else {
            $mess = FALSE;
            $error2 = '<p>Please create a valid message. </p>';
        }
        
        if($mess && $conversation_id && $user_id && $valid_conversation) {
            $error2 = '';
           try {    
               add_conversation_message($conversation_id, $user_id, $mess); 
            } catch(PDOException $e) {
               echo $e->getMessage();
            }
        } 
    }

    if($valid_conversation) {
        if(isset($_POST['message'])) {
            update_conversation_last_view($conversation_id, $user_id);
            $messages = fetch_conversation_messages($user_id, $conversation_id);
        } else {
            $messages = fetch_conversation_messages($user_id, $conversation_id);
            update_conversation_last_view($conversation_id, $user_id);
        }
?>
    <form class = 'form' name='reply_conv' action="" method="post" onsubmit='return reply_conv_form()'>
    <div>
        <h1>Reply to Message:</h1>
        <p>
            <label for='message'>Post message:</label>
            <textarea name="message" id='message' rows=10 cols=110 maxlength=1000></textarea>
            <span id='rcMess_err'><?= $error2 ?></span>
        </p>
        <input type='hidden' name='submitted' value='TRUE'/>
    </div>
    <input type="submit" value="add message"/>
    </form>
<?php
        foreach($messages as $message) {
?>
    <div style="clear: right; width = 100%; <?php if($message['unread']) echo "background-color: grey"?>">
        <table>
            <tr>
                <td width='30%'><a href='index.php?page=view_member&member_id=<?= $message['user_id'] ?>'><?= htmlentities($message['username']) ?></a> <p style='font-size: 15px; display: inline'>( <?= htmlentities($message['date']) ?> ) </p>
                <br /><div style="height: 200px; width=200px">
                <span style='display: inline-block; height: 100%; vertical-align: middle'></span> 
                <img style='width:50%; height:50%; vertical-align: middle' src="getImage.php?id=<?= htmlentities($message['user_id']) ?>">
            </div></td><td style='vertical-align: top; width:60%;'><p style='display: inline'><?= htmlentities($message['text']) ?></p></td>
            </tr>
        </table>
    </div>
<?php
        }
    } else {
        echo '<p>Conversation could not be viewed for reasons:<br />
            ' . $error1 . '<br />' . $error2;
    }
?>
<a href="index.php?page=profile">Back to profile</a><br />
<a href="index.php?page=home">Back to home</a><br />
<a href="index.php?page=forums">To forums</a><br /><br />
<?php
} else {
    header('Location: index.php?page=home&mess=You are not logged in');
    exit();
}
?>