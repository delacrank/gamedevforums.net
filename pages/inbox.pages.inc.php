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
    
    $user_id = $error1 = $error2 = '';
    $user_id = $_SESSION['user_id'];
    if(isset($_GET['delete_conversation'])) {
        $delete_conv = filter_input(INPUT_GET, 'delete_conversation', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        
        if(validate_conversation_id($delete_conv, $user_id) === false) {
            $error1 = '<p>invalid message.</p>';
        } else {
            delete_conversation($delete_conv, $user_id);
        }
    }   
    
    $conversations = fetch_conversation_summary($user_id);

    if(empty($conversations)) {
        $error2 = '<p>You have no conversations</p>';
    } else {
        $error1 = '';
        $error2 = '';
    }
?>
    <h1>Inbox</h1>
    <span><?= $error1 ?></span>
    <span><?= $error2 ?></span>
    <p>Messages:</p>
    <?php
        foreach($conversations as $conversation) {
            ?>
            <div style='width:80%; margin-left: 10%<?php if($conversation['unread']) { 
                echo ';background-color: grey'; 
            }?>'>
            <p>
            <a href='index.php?page=inbox&delete_conversation=<?= htmlentities($conversation['id']) ?>'>[x]</a>
            <a href="index.php?page=view_conversation&conversation_id=<?= htmlentities($conversation['id']) ?>"><?= htmlentities($conversation['subject']) ?></a></p>
            <p>Last Reply: <?= htmlentities($conversation['last_reply']) ?></p>
            </div>
            <?php
        }
    ?>
<a href="index.php?page=home">Back to home</a><br />
<a href="index.php?page=forums">To forums</a><br /><br />
<?php 
} else {
    header('Location: index.php?page=home&mess=You are not logged in');
    exit();
}
?>