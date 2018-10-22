<?php
if(!empty($_GET['member_id'])) {
    $member_id = filter_input(INPUT_GET, 'member_id', FILTER_SANITIZE_STRING);
} else {
    $member_id = '';
}

$row = getProfile($member_id);
?>

<h1 class ='pH1'>Profile Page: <?= $row['username'] ?></h1>
<div class ='vm_col1'>
<div style='height: 200px; width=200px'>
        <span style='display: inline-block; height: 100%; vertical-align: middle'></span> 
        <img style='vertical-align: middle' src="getImage.php?id=<?= $member_id ?>">
</div>
<a <?php if(isset($_SESSION['user_id'])) {
        echo "href='index.php?page=profile&spUsername={$row['username']}'";
    } else {
        echo 'onclick="login_modal()"';
    } ?> > Send Message</a><br />
<a <?php if(isset($_SESSION['user_id'])) {
        echo "href='index.php?page=profile&forum_friend={$row['username']}'";
    } else {
        echo 'onclick="login_modal()"';
    } ?> >Add as a friend</a>
</div>
<div class ='vm_col2'>

<p>About me: <?= $row['about_me'] ?></p>
<p>Location: <?= $row['location'] ?></p>
<p>Age: <?= $row['age'] ?></p>
<p>Gender: <?= $row['gender'] ?></p>
</div>