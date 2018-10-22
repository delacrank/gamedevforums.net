<?php

// validation for login to create session
function login($user, $pass) {
    $conn = db_connect1();
    // check if username is unique
    $query = "SELECT * FROM users WHERE
              username=? AND pass = sha1(?)";
    try {
        $stmt = $conn->prepare($query);
        $execute = $stmt->execute([$user, $pass]);
        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['activation'] != NULL) {
                throw new PDOException('<p>Please activate your account first before attempting to log in.</p>
                <p>Click <a href="index.php">here</a> to go back home');
                $conn = null;
                return false;
            }
            $conn = null;
            return $row;
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// generate a random token for the user's session
function token($sess_user) {
    $conn = db_connect1();
    $tokenId = rand(10000, 9999999);
    // update the token in the user's table
    $query = "UPDATE users SET tokenid = ? WHERE username = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$tokenId, $sess_user]);
        // send the unique token back for session
        if($stmt->rowCount() == 1) {
            $query2 = "SELECT tokenid FROM users WHERE username = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([$sess_user]);
            if($stmt2->rowCount() == 1) {
                $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                $conn = null;
                return $row;
            } else {
                throw new PDOException('<p>User does not exist</p>');
                $conn = null;
                return false;
            }
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// check to see the user's session matches up with token
function check_valid($username) {
    $conn = db_connect1();
    $query = 'SELECT tokenid FROM users WHERE username = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$username]);
        // send unique tokenid back for session
        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $conn = null;
            return $row;
        } else {
            throw new PDOException("<p>User doesn't exist</p>");
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// update the password for a user who forgets it
function forgot_pass($user, $sq) {
    $conn = db_connect1();
    $query = 'SELECT secques, email, username FROM users
              WHERE username = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user]);
        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;
            if($sq == $row['secques']) {
                $email = $row['email'];
                $pass = substr(md5(uniqid(rand(),1)),3, 10);
                $query2 = 'UPDATE users SET pass = SHA1(?) WHERE username = ?';
                $stmt2 = $conn->prepare($query2);
                $stmt2->execute([$pass, $user]);
                if($stmt2->rowCount() == 1) {
                    $body = "Your password has been temporarily changed to '{$pass}'. Please log in using this password and username, you may change it later.";
                    mail($email, 'Temporary password', $body, 'From: WebMaster@GameDevForums.net');
                    $conn = null;
                    return true;
                } else {
                    throw new PDOException('<p>Could not update password</p>');
                    $conn = null;
                    return false;
                }
            }
        } else {
            throw new PDOException('<p>User does not exist</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function register_account($user, $email, $sq, $pass) {
    $conn = db_connect1();
    // first check if there is a duplicate email account
    $query = 'SELECT username FROM users WHERE email = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0) {
            throw new PDOException('<p>Email already taken.</p>');
            $conn = null;
            return false;
        }

        // check to see if there is a duplicate username account
        $query2 = 'SELECT username FROM users WHERE username = ?';
        $stmt2 = $conn->prepare($query2);
        $stmt2->execute([$user]);
        if($stmt2->rowCount() > 0) {
            throw new PDOException('<p>Username already taken.</p>');
            $conn = null;
            return false;
        }

        if(($stmt->rowCount() == 0) && ($stmt2->rowCount() == 0)) {
            $key = md5(uniqid(rand(), true));
            $query3 = 'INSERT INTO users VALUES(null, ?, SHA1(?), ?, ?, null, ?)';
            $stmt3 = $conn->prepare($query3);
            $stmt3->execute([$user, $pass, $email, $sq, $key]);
            if($stmt3->rowCount() == 1) {
                $query4 = 'INSERT INTO profile VALUES (?, ?, null, null, null, null)';
                $stmt4 = $conn->prepare($query4);
                $stmt4->execute([$conn->lastInsertId(), $user]);
                if($stmt4->rowCount() == 1) {
                    $body = "Thanks for registering. Activate account by clicking this link:<br />";
                    $body .= "http://localhost/activate.php?x=" . $conn->lastInsertId() . "&y=$key";
                    mail($email, 'Registration Confirmation', $body, 'From: WebMaster@GameDevForums.net');
                    $stmt2 = null;
                    return true;
                }
            } else {
                throw new PDOException('<p>Could not register user</p>');
                $conn = null;
                return false;
            }
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function activate($userid, $actCode) {
    $conn = db_connect1();
    $query = "UPDATE users SET activation=NULL WHERE (user_id=? AND activation=?) LIMIT 1";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$userid, $actCode]);
        if ($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            throw new PDOException('<br><br><p>Your account could not be activated. Please re-check the link.</p>');   
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function change_password ($username, $old_pass, $new_pass) {
    $conn = db_connect1();
    $query = 'SELECT * FROM users WHERE username = ? AND pass = SHA1(?)';
    try {
        $stmt = $conn->prepare($query);
        $execute = $stmt->execute([$username, $old_pass]);
        if($stmt->rowCount() == 1) {
            $query2 = 'UPDATE users SET pass = sha1(?) WHERE username = ? AND pass = SHA1(?)';
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([$new_pass, $username, $old_pass]);
            if($stmt->rowCount() == 1) {
                $conn = null;
                return true;
            } else {
                throw new PDOException('<p>Your password could not be changed<p>');
                $conn = null;
                return false;
            }
        } else {
            throw new PDOException('<p>Either username or password is incorrect, if you forgot your password, click here <a href="index.php?page=forgot_pass">Forgot Pass</a></p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>