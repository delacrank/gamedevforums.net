<h1>Welcome to the Forums</h1>

    <p>Post on topics present here, you can either ask a question or answer someone's question. The forums are open for discussion's which are relevant to the topics presented.</p>
    
    <?php
    if(!empty($_SESSION['username'])) {
        echo "<a style ='float: right' href='index.php?page=categories'>Post a new thread</a><br /><br />";
    }
    ?>
    
<div class = 'forums_col2'>
    <p>Topics</p>
    <ul>
<?php 
    $row = getCategories();
    foreach($row as $rows) : 
?> 
    <li><a href='index.php?page=display_posts&cat_id=<?= htmlentities($rows["cat_id"]) ?>'><?= htmlentities($rows['cat_title']) ?> </a></li>
<?php 
    endforeach;
?>
    </ul>
</div>    
    
<div class = 'forums_col1'>
<?php 
    $row = getMessagesForum();
?>
    <div class = 'title_forums'>
        <p>Views Replies</p>
    </div>
    <table>
<?php
        foreach($row as $rows) :
?>
        <tr>
            <td colspan=3 style='padding-top: 10px'><a href="index.php?page=display_messages&post_id=<?= htmlentities($rows['post_id']) ?>&cat_id=<?= $rows['cat_id'] ?>"><?= htmlentities($rows['post_title']) ?></a></td>
        </tr>
        <tr>
            <td><?= htmlentities($rows['description']) ?></td>
            <td rowspan=2><?= htmlentities(display_views($rows['cat_id'], $rows['post_id'])) ?></td>
            <td rowspan=2><?= htmlentities(display_replies($rows['cat_id'], $rows['post_id'])) ?></td>
        </tr>
        <tr>
            <td>Posted in: <a href='index.php?page=display_posts&cat_id=<?= htmlentities($rows['cat_id']) ?>'><?= htmlentities($rows['cat_title']) ?></a> Started by:<a href='index.php?page=view_member&member_id=<?= htmlentities($rows['user_id']) ?>'> <?= htmlentities($rows['username']) ?></a></td>
        </tr>

<?php
        endforeach;
?>
    </table>
</div>

<br style = "clear: left" /><br />
<a href='index.php?page=home'>Back home</a>
<br /><br />