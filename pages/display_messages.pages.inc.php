<?php
    if(!empty( $_GET['post_id'] )) {
        $post_id = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    } else {
        $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    if(!empty( $_GET['cat_id'] )) {
        $cat_id = filter_input(INPUT_GET, 'cat_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    } else {
        $cat_id = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    $user_id = $message = $error1 = $error2 = '';
    if(!empty($_POST['submitted'])) {        
        if(stripslashes(trim($_POST['user_id']))) {
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            $error1 = '';
        } else {
            $user_id = FALSE;
            $error1 = '<p>You are not logged in.</p>';
        }

        if(stripslashes(trim($_POST['rfMessage']))) {
            $message = filter_input(INPUT_POST, 'rfMessage', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $error2 ='';
        } else {
            $message = FALSE;
            $error2 = '<p>Invalid message format, please try again.</p>';
        }

        if( $user_id && $post_id && $cat_id && $message) {
            create_message($user_id, $post_id, $cat_id, $message);
        }
    }

    $userIp = getIp();

    storeIp( $userIp, $cat_id, $post_id );

    $row = getPostsByPost($post_id);
    $count = 1;
?>
    <div class = 'display_table'>
        <table class ='display_titles'>
            <tr>
                <td colspan=2><?php echo htmlentities($row['date']) . '<span>' . $count .'</span>'; ?></td>
            </tr>
            <tr>
                <td>
                    <div>
                        <a href='index.php?page=view_member&member_id=<?= $row['user_id'] ?>'><?= htmlentities($row['username']) ?> </a>
                        <a id='hov_mess' <?php if(isset($_SESSION['user_id'])) {
                            echo "href='index.php?page=profile&spUsername= {$row['username']}'";
                        } else {
                            echo 'onclick="login_modal()"';
                        } ?> >Send Message</a>
                    </div>
                     
                    <div style="margin: auto">
                        <span style='display: inline-block; height: 100%; vertical-align: middle'></span> 
                        <img src="getImage.php?id=<?= htmlentities($row['user_id']) ?>">
                    </div>
                </td>
                <td><b><?= $row['post_title'] ?></b><br />
                    <hr />
                <?= htmlentities($row['description']) ?></td>
            </tr>
            <tr>
               <td colspan=2></td>
            </tr>
        </table>
<?php
    $count = 2;
    $messages = getMessages($post_id);
            
    if(!is_array($messages)) {
        $messages = array();
    }
            
    foreach($messages as $row) : 
?>
    <table class = 'display_responses'>
        <tr>
            <td colspan=2><?php echo htmlentities($row['date']) . '<span>' . $count .'</span>'; ?></td>
        </tr>
        <tr>
            <td>
                <div>
                    <a href='index.php?page=view_member&member_id=<?= $row['user_id'] ?>'><?= htmlentities($row['username']) ?>
                    </a>
                    <a <?php if(isset($_SESSION['user_id'])) {
                            echo "href='index.php?page=profile&spUsername= {$row['username']}'";
                        } else {
                            echo 'onclick="login_modal()"';
                        } ?> >Send Message</a>
                </div>
                <div style="margin: auto">
                    <span style='display: inline-block; height: 100%; vertical-align: middle'></span> 
                    <img src="getImage.php?id=<?= htmlentities($row['user_id']) ?>">
                </div>
            </td>
            <td><?= htmlentities($row['message_txt']) ?></td>
        </tr>
        <tr>
           <td colspan=2></td>
        </tr>
        <br />
<?php
    ++$count;
    endforeach;
?>
    </table>
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
?>    
    <br />
    <form class = 'form' name='reply_forum' action="" method='post' onsubmit="return reply_forum_post()">
    <div>
    <h1>Reply to thread</h1>
    <fieldset>
        <p>
            <label for='rfMessage'>Reply:</label><br />
            <textarea name='rfMessage' id='rfMessage' cols=15 rows=5 maxlength=1000></textarea>
            <span id='rfMessage_err'><?= $error1 ?></span>
        </p>
        
        <input type='hidden' name='user_id' value='<?= $_SESSION['user_id'] ?>'/>
        <input type='hidden' name='post_id' value='<?= $post_id ?>'/>
        <input type='hidden' name='cat_id' value='<?= $cat_id ?>'/>
        <input type='hidden' name='submitted' value='TRUE'/>
        
    </fieldset>
    </div>
    <input type='submit' value='reply'/>
    </form>
<?php
    }
?>