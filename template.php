<?php

require_once('functions.php');

$core_path = dirname(__FILE__);

if(!empty($_GET['page'])) {
    $path = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
} else {
    $path = FALSE;
    echo '<p>no page specified</p>';
}

$title = setTitle($path);   

if(!$path 
  || (in_array("$path.pages.inc.php", scandir("{$core_path}/pages")) == false 
&& in_array("$path.articles.inc.php", scandir("{$core_path}/articles")) == false)) 
{ 
    header('HTTP/1.1 404 Not Found');
    header("Location: index.php?page=home&mess=page not found");
    exit();
} 

session_start();

if(file_exists("{$core_path}/pages/{$path}.pages.inc.php")) {
    $include_file = "{$core_path}/pages/{$path}.pages.inc.php";
} else if (file_exists("{$core_path}/articles/{$path}.articles.inc.php")) {
    $include_file = "{$core_path}/articles/{$path}.articles.inc.php";
} else {
     header('HTTP/1.1 404 Not Found');
     header("Location: index.php?page=home&mess=page not found");
     exit();
}

?>