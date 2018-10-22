<?php
    include('../functions.php');

    if(!empty($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    } else {
        $id = '';
    }

    $dir = dirname(__FILE__);
    
    if(!checkImageAmount($id)) {
        header("Content-Type: jpg");
        readfile(substr($dir, 0, -6) .'/images/default.jpg');
    } else {
        $row = array();
        try {
            $row = getImage($id);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        header("Content-Type:" .$row['img_type']);
        readfile(substr($dir, 0, -6) .'/images/' . $row['img_name']);
    }
?>
