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
    
    $user_id = $_SESSION['user_id'];

    if(!empty($_GET['cat_id'])) {
        $catid = filter_input(INPUT_GET, 'cat_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    } else {
        $catid = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    $post_title = $post_desc = $error1 = $error2 = '';
    if(!empty($_POST['submitted'])) {
        // enter a valid title
        if (stripslashes(trim($_POST['post_title']))) {
            $post_title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $error1 = '';
        } else {
            $post_title = FALSE;   
            $error1 = '<p>Please enter a valid title.<p>';
        }
        // enter a valid message
        if (stripslashes(trim($_POST['post_desc']))) {
            $post_desc = filter_input(INPUT_POST, 'post_desc', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $error2 = '';
        } else {
            $post_desc = FALSE;
            $error2 = '<p>Please enter a valid subject</p>';
        }

        if($post_title && $post_desc && $catid && $user_id) {
            if(create_post($post_title, $post_desc, $catid, $user_id)) {
                $post_title = $post_desc = $catid = $user_id = '';
            }
        }
    }
    ?>
    


    <form class = 'form' name='create_post' action ='' method="post" onsubmit='return create_forum_post()'>
        <div>
            <h1>Write your post here</h1>
            <fieldset>
            <p>
               <label for='post_title'>Post title:</label>
               <br /><input type='text' id ='post_title' name='post_title' size=40 maxlength=40/>
                <span id='cPost_title_err'><?= $error1 ?></span>
            </p>
            
            <p>
                <label for='post_desc'>Post Description:</label>
                <br /><textarea  rows=5 cols=50 id='post_desc' name='post_desc' maxlength=2000></textarea>
                <span id='cPost_desc_err'><?= $error2 ?></span>
            </p>
            
            <input type='hidden' name='cat_id' value='<?= $catid ?>'/>
            <input type='hidden' name='submitted' value='TRUE'/>

            </fieldset>
        </div>
        <input type='submit' value='submit post'/>
    </form>

    <br /><br />
    <a href='index.php?page=forums'>Back to forums</a><br /><br />
    <a href='index.php?page=home'>Back to home</a>
    <br />
    <?php   
 } else {
    header('Location: index.php?page=home&mess=You are not logged in');
    exit();
}
?>