<?php 
if(!empty($_GET['mess'])) {
    $mess = filter_input(INPUT_GET, 'mess', FILTER_SANITIZE_STRING);
} else {
    $mess = '';
}
?>
    <p><?= $mess ?></p>

<h1>Welcome to the Game Dev Network</h1>

<div class ='home_col1'>
<p>These are the game dev forums, make yourself at home, if you want to view the forums please first either create an account by <a href="index.php?page=registration">registering here</a> or if you have an existing account <a href="index.php?page=login">login here.</a> 

<span class = "nl">If you <a href="index.php?page=forgot_pass">forgot your password</a> follow this link instead instead.</span>

<span class = "nl">View the <a href="index.php?page=forums">forums</a> here.</span>
    
<span class = 'nl'>Read articles about <a href='index.php?page=articles'>game development</a> here.</span> 
</p>
</div>
<div class = 'home_col2'>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</div>
   