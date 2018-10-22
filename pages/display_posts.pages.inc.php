<?php

    if(!empty($_GET['cat_id'])) {
        $catId = filter_input(INPUT_GET, 'cat_id', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    echo '<br /><br />';
    
    if(!empty($_SESSION['username'])) {
        echo '<a href="index.php?page=create_post&cat_id='.htmlentities($catId).'">Start a Discussion</a>';
    }
    
    $rows = getPostsByCat($catId);
    if($catId) {
        echo "<h1>{$rows[0]['cat_title']}</h1><br />";
    } 
?>
    <table class ='display_posts'>
        <tr>
            <td>Title</td>
            <td>Messages</td>
            <td>Views</td>
            <td>Replies</td>
        </tr>
<?php
    foreach ($rows as $row) :
?>
        <tr>
            <td><a href="index.php?page=display_messages&post_id=<?= htmlentities($row['post_id']) . '&cat_id='. htmlentities($row['cat_id']) ?>"><?= htmlentities($row['post_title']) ?></a></td>
            <td><?= htmlentities($row['description']) ?> </td>   
            <td><?= htmlentities(display_views($row['cat_id'], $row['post_id'])) ?></td>
            <td><?= htmlentities(display_replies($row['cat_id'], $row['post_id'])) ?></td>
        </tr>
<?php
    endforeach; 
?>
    </table>