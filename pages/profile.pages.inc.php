<?php

ob_start();

if(!empty($_SESSION['username'])) {
    if($row = check_valid($_SESSION['username'])) {
        if($_SESSION['tokenid'] == $row['tokenid']) {
            $loggedin = 1;
            echo "<p>Greetings, {$_SESSION['username']}</p>";
        } else {
            echo "You are not logged in.";
            $loggedin = 0;
            exit();
        }
    }
} 

if(!empty($_SESSION['username']) && (substr($_SERVER['PHP_SELF'], -6) != 'logout') && $loggedin) {
?>
<div class='profile_col1'>
    <a href="index.php?page=logout">Logout</a><br />
    <a href="index.php?page=forums">Visit the forums</a><br />
    <a href="index.php?page=home">Back Home</a><br /><br />
    <div style="height: 200px; width=200px; text-align: left">
        <span style='display: inline-block; height: 100%; vertical-align: middle'></span> 
        <img style='width:60%; height:60%; vertical-align: middle' src="getImage.php?id=<?= $_SESSION['user_id'] ?>">
    </div>
</div>

<div class = 'profile_col2'>       
    <ul>
        <li class = 'pPanel_buttons' onclick='choosePanel(1)'>Info</li>
        <li class = 'pPanel_buttons' onclick='choosePanel(2)'>Change Pass</li>
        <li class = 'pPanel_buttons' onclick='choosePanel(3)'>Send Message</li>
        <li class = 'pPanel_buttons' onclick='choosePanel(4)'>Inbox</li>
        <li class = 'pPanel_buttons' onclick='choosePanel(5)'>Friend List</li>
    </ul>
<!-- profile 1 ----------------------------------------------->
    <div class = 'pPanel'>
        <?php
            $dir = dirname(__FILE__);
            $profile = $location = $about = $age = $gender = $image = $success1 = $success2 = $success3 = $success4 = $success5 = $upload = $mess = '';
            $profile = getProfile($_SESSION['user_id']);

            if(!empty($_POST['ciSubmitted'])) {
                if(stripslashes(trim($_POST['location'])) && ($_POST['save'])) {
                    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    if(setLocation($location, $_SESSION['user_id'])) {
                        $success1 = '<p>Your location has been set</p>';
                    } else {
                        $success1 = '<p>Location could not be set</p>';
                    }
                } else {
                    $location = FALSE;   
                    $success1 = '<p>Please enter where you are from.<p>';
                }

                if (!empty($_POST['location_clear'])) {
                    if(unsetLocation($_SESSION['user_id'])) {
                        $success1 = '<p>Your location has been removed</p>';
                    } else {
                        $success1 = '<p>Location could not be unset</p>';
                    }
                }
            }

            if(!empty($_POST['ciSubmitted1'])) {
                if(stripslashes(trim($_POST['about_me'])) && ($_POST['save2'])) {
                    $about = filter_input(INPUT_POST, 'about_me', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                    if(setAbout($about, $_SESSION['user_id'])) {
                        $success2 = '<p>Your about me has been set</p>';
                    } else {
                        $success2 = '<p>Your about me could not be set</p>';
                    }
                } else {
                    $about = FALSE;
                    $success2 = '<p>Please enter some information about yourself</p>';
                }

                if (!empty($_POST['about_me_clear'])) {
                    if(unsetAbout($_SESSION['user_id'])) {
                        $success2 = '<p>Your About me has been removed</p>';
                    } else {
                        $success2 = '<p>About me could not be unset</p>';
                    }
                }
            }

            if(!empty($_POST['ciSubmitted2'])) {
                if(stripslashes(trim($_POST['age'])) && ($_POST['save3'])) {
                    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
                    if(setAge($age, $_SESSION['user_id'])) {
                        $success3 = '<p>Your age has been set</p>';
                    } else {
                        $success3 = '<p>Your age could not be set</p>';
                    }
                } else {
                    $age = FALSE;
                    $success2 = '<p>Please enter your age.</p>';
                }

                if (!empty($_POST['age_clear'])) {
                    if(unsetAge($_SESSION['user_id'])) {
                        $success3 = '<p>Your age has been removed</p>';
                    } else {
                        $success3 = '<p>Age me could not be unset</p>';
                    }
                }
            }

            if(!empty($_POST['ciSubmitted3'])) {
                if(stripslashes(trim(!empty($_POST['gender']))) && (!empty($_POST['save4']))) {
                    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
                    if(setGender($gender, $_SESSION['user_id'])) {
                        $success4 = '<p>Your gender has been set</p>';
                    } else {
                        $success4 = '<p>Your gender could not be set</p>';
                    }
                } else {
                    $about = FALSE;
                    $success4 = '<p>Please enter your gender.</p>';
                }

                if (!empty($_POST['gender_clear'])) {
                    if(unsetGender($_SESSION['user_id'])) {
                        $success4 = '<p>Your gender has been removed</p>';
                    } else {
                        $success4 = '<p>Gender not be unset</p>';
                    }
                }
            }

            if(!empty($_POST['ciSubmitted4'])) {
                if(!empty($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $avatar = $_FILES['image']['tmp_name'];
                    if(check_mime($avatar)) {
                        if(!checkImageAmount($_SESSION['user_id'])) {
                            $image_name = $_FILES['image']['name'];
                            $ext = explode(".", $image_name);
                            $new_image_name = md5(uniqid(rand(0, time()), true)) .  '.' . end($ext);
                            $uploadfile = '../images/' . $new_image_name;
                            $type = get_mime($avatar);
                            $image_location = substr($dir, 0, -6) . '/images/' . $new_image_name; 
                            if (move_uploaded_file($avatar, $uploadfile)) {
                                process_image(200, $image_location, $uploadfile);
                                if(setImage($_SESSION['user_id'], $new_image_name, $image_name, $type)) {
                                    echo "<p>Image succesfully uploaded.</p>";
                                    $ext = $avatar = $uploadfile = $new_image_name = $image_name = $type = $image_location = '';
                                }
                            } else {
                                echo "Image uploading failed.";
                            }
                        } else {
                            echo "<p>Can't upload more then one image.</p>";
                        }
                    } else {
                        echo '<p>File is either not a valid image type.</p>';
                    }
                } else {
                    echo '<p>There was an error uploading your file, please reduce the file size or check your connection.</p>';
                }
            }

            if(!empty($_POST['ciSubmitted5'])) {
                if(checkImageAmount($_SESSION['user_id'])) {
                    $row = getImage($_SESSION['user_id']);
                    $image_location = substr($dir, 0, -6) . '/images/' . $row['img_name'];
                    unlink($image_location);
                    deleteImage($_SESSION['user_id']);
                } else {
                    echo '<p>No images to delete</p>';
                }
            }
        ?>    
            <h1>Basic Information</h1>

            <p>Click on any of these fields to set their properties.</p>
            <table>
                <tr>
                    <!-- button -->
                    <td>
                        <p class='pForm_buttons' onclick='chooseDisplay(1)'>Location: <?= htmlspecialchars($profile['location']) ?></p>
                    </td>
                    <td>
                        <!-- form -->    
                        <form class='pForm_display' name='pLocation' action="" method="post">
                            <p>
                            <label for="location">Location:</label>    
                            <input id= 'location' type='text' name='location'  maxlength=30 />
                            </p>
                            <input type='hidden' name='ciSubmitted' value='TRUE'/>
                            <input type="submit" name = 'save' value="save"/>
                            <input type="submit" name = 'location_clear' value="clear"/>
                            <span><?= $success1 ?></span>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <!-- button -->    
                        <p class='pForm_buttons' onclick='chooseDisplay(2)'>About me: <?= htmlspecialchars($profile['about_me']) ?></p>
                    </td>
                    <td>
                        <!-- about me -->
                        <form class='pForm_display' name='pAbout_me' action="" method="post">
                            <p>
                            <label for="about_me">About Me:</label>
                            <textarea id ='about_me' rows=5 cols=20 maxlength=1000 name="about_me"></textarea>
                            </p>
                            <input type='hidden' name='ciSubmitted1' value='TRUE'/>
                            <input type="submit" name = 'save2' value="save"/>
                            <input type="submit" name = 'about_me_clear' value="clear"/>
                            <span><?= $success2 ?></span>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <!-- button -->
                        <p class='pForm_buttons' onclick='chooseDisplay(3)'>Age: <?= htmlspecialchars($profile['age']) ?></p>
                    </td>
                    <td>
                        <!-- age -->
                        <form class='pForm_display' name = 'pAge' action="" method="post">
                            <p>
                            <label for="age">Age:</label>
                            <input type='number' name ='age' max=100>
                            </p>
                            <input type='hidden' name='ciSubmitted2' value='TRUE'/>
                            <input type="submit" name = 'save3' value="save"/>
                            <input type="submit" name = 'age_clear' value="clear"/>
                            <span><?= $success3 ?></span>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <!-- button -->
                        <p class='pForm_buttons' onclick='chooseDisplay(4)'>Gender: <?= htmlspecialchars($profile['gender']) ?></p>
                    </td>
                    <td>
                        <!-- gender -->
                        <form class='pForm_display' name='pGender' action="" method="post">
                            <p>
                            <label for="gender">Gender:</label>
                            <input type='radio' name='gender' value='male'/>Male
                            <input type='radio' name='gender' value='female'/>Female
                            </p>
                            <input type='hidden' name='ciSubmitted3' value='TRUE'/>
                            <input type="submit" name = 'save4' value="save"/>
                            <input type="submit" name = 'gender_clear' value="clear"/>
                            <span><?= $success4 ?></span>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <!-- button -->
                         <p class='pForm_buttons' onclick='chooseDisplay(5)'>Change Avatar:</p>
                    </td>
                    <td>
                        <!-- form -->
                        <div class='pForm_display'>
                            <form name='pImage' action="" method="post" enctype='multipart/form-data'>
                                <p>
                                <label for="image">Select Image:</label>
                                <input style='display: none' id='image' type='file' name='image'/>
                                </p>
                                <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                                <input type='hidden' name='ciSubmitted4' value='TRUE'/>
                                <input type='submit' value='submit'/>
                            </form>
                            <form action="" method="post" enctype='multipart/form-data'>
                                <p>
                                <label for="delete">Delete Image:</label>
                                </p>
                                <input type='hidden' name='ciSubmitted5' value='TRUE'/>
                                <input type='submit' id='delete' value='delete'/>
                            </form>
                        </div>
                    </td>
                </tr>
            </table>
    </div>
        
<!-- profile 2 ----------------------------------------------->
    <div class="pPanel">
        <?php
                $username = $old_pass = $new_pass = $new_pass2 = $cperror1 = $cperror2 = $cperror3 = $cperror4 = '';
            if(!empty($_POST['cpSubmitted'])) {
                if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['username'])))) {
                    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                    $cperror1 = '';
                } else {
                    $username = FALSE;
                    $cperror1 = '<p>Username should have one uppsercase letter and at least a digit.</p>';
                }

                if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['old_pass'])))) {
                    $old_pass = filter_input(INPUT_POST, 'old_pass', FILTER_SANITIZE_STRING);
                    $cperror2 = '';
                } else {
                    $old_pass = FALSE;
                    $cperror2 = '<p>Must submit a password</p>';
                }

                if(preg_match('/\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{8,}\z/', stripslashes(trim($_POST['new_pass'])))) {
                    if($_POST['old_pass'] != $_POST['new_pass']) {
                        if ($_POST['new_pass'] == $_POST['new_pass2']) {
                            $new_pass = filter_input(INPUT_POST,'new_pass', FILTER_SANITIZE_STRING);
                            $cperror3 = $cperror4 = '';
                        } else {
                            $new_pass = FALSE;
                            $cperror3 = '<p>Your passwords do not match</p>';
                        }
                    } else {
                        $new_pass = FALSE;
                        $cperror3 = '<p>Your new pass must be different then your old pass</p>';
                    }
                } else {
                    $new_pass = FALSE;
                    $cperror4 = '<p>Password must contain at least 8 characters, a lowercase, uppercase and a number.</p>';
                }

                if($username && $old_pass && $new_pass) {
                    if(change_password($username, $old_pass, $new_pass)) {
                        $username = $old_pass = $new_pass = $new_pass2 = '';
                        header('Location: index.php?page=logout&mess=Your password has been successfully changed');
                    }
                }
            }
        ?>
        <h1>Change Password</h1>
        
        <p>You can change your password here, input the old password in the first box. Make sure that your new passwrod contains an uppercase char and a number.</p>


        <form class = 'form' name='change_password' action='' method='post' onsubmit='return change_profile_password()'>

            <fieldset>
            <p>
                <label for='cpusername'>Username:</label>
                <input id='cpusername' type='text' name='username' value='<?= $username ?>' />
                <span id='cpUsername_err'><?= $cperror1 ?></span>
            </p>
            <p>
                <label for='old_pass'>Password:</label>
                <input id='old_pass' type='password' name='old_pass' />
                <span id='cpOld_pass_err'><?= $cperror2 ?></span>
            <p>
                <label for='new_pass'>New Password:</label>
                <input id='new_pass' type='password' name='new_pass' />
                <span id='cpNew_pass_err'><?= $cperror3 ?></span>
            </p>
            <p>
                <label for='new_pass2'>Confirm New Password:</label>
                <input id='new_pass2' type='password' name='new_pass2' />
                <span id='cpNew_pass2_err'><?= $cperror4 ?></span>
            </p>
            <input type='hidden' name='cpSubmitted' value='TRUE'/>
            </fieldset>

            <input type='submit' value='change password'/>
        </form>

        <br /><br />
        <a href='index.php?page=home'>Home</a><br /><br />
        <a href='index.php?page=forums'>Forums</a><br />
    </div>
    
<!-- profile 3 ----------------------------------------------->
    <div class="pPanel">
        <?php
            $to = $body = $subject = $success = $sperror1 = $sperror2 = $sperror3 = $sperror4 = '';
             
            if (!empty($_GET['spUsername'])) {
                $spUsername = filter_input(INPUT_GET, 'spUsername', FILTER_SANITIZE_STRING);
            } else {
                $spUsername = '';
            }
            
            
        if(!empty($_POST['spSubmitted'])) {
            //if (preg_match('%^[a-z, ]+$%i', trim($_POST['to']))) {
            if (stripslashes(trim($_POST['to']))) {
    //            if(strpos($_POST['to'], $_SESSION['username']) !== false) {
    //                $to = FALSE;
    //                $error1 = '<p>Cannot send message to yourself</p>';
    //            } else {
                $to = filter_input(INPUT_POST, 'to', FILTER_SANITIZE_STRING);
                $user_names = explode(',', $to);
                foreach($user_names as &$u) {
                    $u = trim($u);
                    if($u == $_SESSION['username']) {
                        $to = FALSE;
                        $sperror4 = '<p>Cannot send message to yourself.</p>';
                    }
                }
                if($user_ids = fetch_user_ids($user_names)) {
                    if(count($user_ids) !== count($user_names)) {
                        $to = FALSE;
                        $sperror4 = '<p>Some users could not be found' . implode(', ', array_diff($user_names, array_keys($user_ids)));
                    }
                } else {
                    $sperror4 = '<p>No users match that username</p>';
                    $to = FALSE;
                }
                $sperror1 = '';
            } else {
                $to = FALSE;   
                $sperror1 = '<p>Please enter a valid username.<p>';
            }
            // enter a valid email address
            if (stripslashes(trim($_POST['subject']))) {
                $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $sperror2 = '';
            } else {
                $subject = FALSE;
                $sperror2 = '<p>Please enter a valid subject.</p>';
            }

            if (stripslashes(trim($_POST['body']))) {
                $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                $sperror3 = '';
            } else {
                $from = FALSE;
                $sperror3 = '<p>Please enter valid input for this field</p>';
            }

            $user_id = $_SESSION['user_id'];

            if( $to && $subject && $body && $user_id && $user_ids) {
                create_conversation(array_unique($user_ids), $user_id, $subject, $body);
                $success = "<p style='clear: both'>Success your message has been sent.";
                $to = $subject = $body = '';
                $user_ids = array();
            }
        }
    ?>
        <h1>Send private Message</h1>
        <p>Send a message to one of the users.</p>
        <form class="form" action="" name="create_message" method="post" onsubmit="return create_message_form()">
            <fieldset>
                <p>
                    <label for='to'>To:</label>
                    <input type='text' id='to' name='to' value='<?php if($spUsername) {
                        echo $spUsername; 
                    } else {
                        echo $to; 
                    } ?>'/>
                    <span id='mTo_err'><?= $sperror1 ?></span>
                    <span><?= $sperror4 ?></span>
                </p>
                <p>
                    <label for='subject'>Subject:</label>
                    <input type='text' id='subject' name='subject' value='<?= $subject ?>'/>
                    <span id='mSubject_err'><?= $sperror2 ?></span>
                </p>
                <p>
                    <label for='body'>Message:</label>
                    <textarea id='body' name='body' rows=5 cols=30><?= $body ?></textarea>
                    <span id='mBody_err'><?= $sperror3 ?></span>
                </p>
                <input type='hidden' name='spSubmitted' value='true'/>
            </fieldset>    
            <input type='submit' value='send message'/>
        </form>
        <span><?= $success ?></span>
        <br /><br />

        <a href="index.php?page=home">Back to home</a><br />
        <a href="index.php?page=forums">To forums</a><br /><br />
    </div>
    
<!-- profile 4 ----------------------------------------------->
    <div class="pPanel">
        <?php
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
                    <a href='index.php?page=profile&delete_conversation=<?= htmlentities($conversation['id']) ?>'>[x]</a>
                    <a href="index.php?page=view_conversation&conversation_id=<?= htmlentities($conversation['id']) ?>"><?= htmlentities($conversation['subject']) ?></a></p>
                    <p>Last Reply: <?= htmlentities($conversation['last_reply']) ?></p>
                    </div>
                    <?php
                }   
        ?>
    <a href="index.php?page=home">Back to home</a><br />
    <a href="index.php?page=forums">To forums</a><br /><br />
    </div>                 
<!-- profile 5 ----------------------------------------------->
    <div class="pPanel">
        <?php
            $forum_friendName = $fl_friendName = $fl_error1 = '';
            $user_id = $_SESSION['user_id'];
            if(isset($_GET['forum_friend'])) {
                $forum_friendName = filter_input(INPUT_GET, 'forum_friend', FILTER_SANITIZE_STRING);
            } else {
                $forum_friendName = FALSE;
            }
            
            if(isset($_GET['mess'])) {
                $mess = filter_input(INPUT_GET, 'mess', FILTER_SANITIZE_STRING);
            } else {
                $mess = '';
            }

            if(isset($_GET['delete_friend'])) {
                $fl_delete = filter_input(INPUT_GET, 'delete_friend', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

                if(removeFriend($user_id, $fl_delete) === false) {
                    $fl_error1 = '<p>Invalid friend Id</p>';
                } else {
                    header('Location: index.php?page=profile&mess=friend deleted');
                }
           }

           if($forum_friendName) {
                if(addFriend($user_id, $forum_friendName)) {
                    echo '<p>Friend successfully added</p>';
                    $forum_friendName = $fl_friendName = $fl_error1 = '';
                } else {
                    echo '<p>Failed to add friend</p>';
                }
           }

           if(!empty($_POST['flSubmitted'])) {
                if(stripslashes(trim($_POST['fl_name']))) {
                    $fl_friendName = filter_input(INPUT_POST, 'fl_name', FILTER_SANITIZE_STRING);
                    $fl_error1 = '';
                } else {
                    $fl_friendName = FALSE;
                    $fl_error1 = '<p>User could not be found, please check name and try again.</p>';
                }
                
                if($fl_friendName == $_SESSION['username']) {
                    $fl_friendName = FALSE;
                    $fl_error1 = '<p>Cannot add yourself to the friendlist.</p>';
                }

                if($fl_friendName && $user_id) {
                    if(addFriend($user_id, $fl_friendName)) {
                        $forum_friendName = $fl_friendName = $fl_error1 = $mess = '';
                        echo '<p>Friend successfully added.</p>';
                    } else {
                        echo '<p>Friend could not be added</p>';
                    }
                }
            }
        ?>
        <h1>Friend List</h1>
        <p>Add or remove friends from your list.</p>
        <p><?= $mess ?></p>
        <?php
            $friends = showFriends($user_id);
            if(is_array($friends)) {
                foreach($friends as $friend) {
                    ?>
                    <div class = 'friend_list'>
                    <div class = 'friend_info'>
                        <a href='index.php?page=profile&delete_friend=<?= htmlentities($friend['friend_id']) ?>'>[x]</a>
                        <div style="display: inline-block; height: 100px; width: 100px; text-align: left">
                            <span style='display: inline-block; height: 100%; vertical-align: middle'></span> 
                            <img style='width:60%; height:60%; vertical-align: middle' src="getImage.php?id=<?= htmlentities($friend['friend_id']) ?>">
                        </div>
                    </div>
                    <div class = 'friend_list_message'>
                        <span><a href='index.php?page=view_member&member_id=<?= htmlentities($friend['friend_id']) ?>'><?= htmlentities($friend['friendname']) ?></a></span>
                        <span><a href='index.php?page=profile&spUsername=<?= htmlentities($friend['friendname']) ?>'>Message</a></span>
                    </div>
                    </div>
                    <?php
                }
            } 
        ?>
        <form class="form" action="" name="friend_list" method="post" onsubmit="return friend_list_form()">
            <fieldset>
                <p>
                    <label for='fl_name'>Friend Name:</label>
                    <input type='text' id='fl_name' name='fl_name' value='<?= $fl_friendName ?>'/>
                    <span id='fl_name_err'><?= $fl_error1 ?></span>
                </p>
                <input type='hidden' name='flSubmitted' value='true'/>
            </fieldset>    
            <input type='submit' value='Add friend'/><br /><br />
        </form>
            <a href="index.php?page=home">Back to home</a><br />
    <a href="index.php?page=forums">To forums</a><br /><br />
    </div>
<script src='../JS/profile_panel.js'></script>

<!-- end profile 5 -------------------------------------->
<?php        
     ob_end_flush();
} else {
    header('Location: index.php?page=home&mess=You are not logged in');
    exit();
}
?>