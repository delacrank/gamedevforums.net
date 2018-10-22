<?php
function check_mime ($upload) {
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = array('image/png', 'image/jpeg', 'image/gif');
    if (in_array(finfo_file($fileinfo, $upload), $mime_type)) {
        finfo_close($fileinfo);
        return true;
    } else {
        finfo_close($fileinfo);
        return false;
    }  
}

function get_mime($upload) {
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($fileinfo, $upload);
    finfo_close($fileinfo);
    return $type;
}

function setLocation($location, $user_id) {
    $conn = db_connect1();
    $query = "UPDATE profile SET location = ? WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$location, $user_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function unsetLocation($user_id) {
    $conn = db_connect1();
    $query = "UPDATE profile SET location = null WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function setAbout($about, $user_id) {
    $conn = db_connect1();
    $query = "UPDATE profile SET about_me = ? WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $execute = $stmt->execute([$about, $user_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            throw new PDOException('<p>There was a problem with updating your age.<p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function unsetAbout($user_id) {
    $conn = db_connect1();
    $query = "UPDATE profile SET about_me = null WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 1) {
            $stmt= null;
            return true;
        } else {
            $stmt = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function setAge ($age, $user_id) {
    $conn = db_connect1();
    $query = 'UPDATE profile SET age = ? WHERE user_id = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$age, $user_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function unsetAge($user_id) {
    $conn = db_connect1();
    $query = "UPDATE profile SET age = null WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function setGender ($gender, $user_id) {
    $conn = db_connect1();
    $query = 'UPDATE profile SET gender = ? WHERE user_id = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$gender, $user_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            $conn = null;
            return true;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function unsetGender ($user_id) {
    $conn = db_connect1();
    $query = "UPDATE profile SET gender = null WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getProfile ($user_id) {
    $conn = db_connect1();
    $query = 'SELECT * FROM profile WHERE user_id = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
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

function checkImageAmount($user_id) {
    $conn = db_connect1();
    $query = "SELECT * FROM images WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 0) {
            $stmt = null;
            return false;
        } else {
            $stmt = null;
            return true;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function setImage($user_id, $newImgName, $oldImgName, $type) {
    $conn = db_connect1();
    $query = "SELECT * FROM images WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() >= 1) {
            throw new PDOException("<p>Sorry can't upload more then one image</p>");
            $conn = null;
            return false;
        }
        $query2 = "INSERT INTO images VALUES ( ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->execute([$user_id, $newImgName, $oldImgName, $type]);
        if($stmt2->rowCount() == 1) {
            $conn = null;
            return true;
        } else {
            throw new PDOException('<p>There was a problem uploading your image<p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function process_image($size, $image, $upload_location) {
    $type = get_mime($image);
    
    if($type == 'image/jpeg') {
        $image_from_file = 'imagecreatefromjpeg';
        $image_to_file = 'imagejpeg';
    } else if ($type == 'image/png') {
        $image_from_file = 'imagecreatefrompng';
        $image_to_file = 'imagepng';
    } else if ($type == 'image/gif') {
        $image_from_file = 'imagecreatefromgif';
        $image_to_file = 'imagegif';
    } else {
        $image_to_file = false;
        $image_from_file = false;
    }

    if(($image_to_file !== false) && ($image_from_file !== false)) {
        $old_image = $image_from_file($image);
        $old_width = imagesx($old_image);
        $old_height = imagesy($old_image);

        // Calculate height and width ratios
        $width_ratio = $old_width / $size;
        $height_ratio = $old_height / $size;

        // If image is larger than specified ratio, create the new image
        if ($width_ratio > 1 || $height_ratio > 1) {

            // Calculate height and width for the new image
            $ratio = max($width_ratio, $height_ratio);
            $new_height = round($old_height / $ratio);
            $new_width = round($old_width / $ratio);

            // Create the new image
            $thumb = imagecreatetruecolor($new_width, $new_height);

            if ($type == 'image/gif') {
                $alpha = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                imagecolortransparent($thumb, $alpha);
            }
            if ($type == 'image/png' || $type == 'image/gif'){
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
            }

            imagecopyresampled($thumb, $old_image,
                               0, 0, 0, 0,
                               $new_width, $new_height, $old_width, $old_height);

            // Write the new image to a new file
            $image_to_file($thumb, $upload_location);

            // Free any memory associated with the new image
            imagedestroy($thumb);
        }
    } else {
        echo '<p>Invalid file type</p>';
        exit();
    }
}

function getImage($user_id) {
    $conn = db_connect1();
    $query = "SELECT img_name, img_org_name, img_type FROM images WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
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

function deleteImage($user_id) {
    $conn = db_connect1();
    $query = "SELECT * FROM images WHERE user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 0) {
            throw new PDOException("<p>There are no images to delete.</p>");
            $conn = null;
            return false;
        }
        $query2 = "DELETE FROM images WHERE user_id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->execute([$user_id]);
        if($stmt2->rowCount() == 1) {
            $conn = null;
            echo '<p>Your image has successfully been deleted.</p>';
        } else {
            throw new PDOException('<p>There was a problem uploading your image<p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addFriend($user_id, $friendName) {
    $conn = db_connect1();
    $query = 'SELECT * FROM users WHERE username = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$friendName]);
        if($stmt->rowCount() == 0) {
            throw new PDOException('<p>User no longer exists.</p>');
            $conn = null;
            return false;
        } 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $friend_id = $row['user_id'];
        $query2 = 'SELECT * FROM friends WHERE user_id = ? AND friend_id = ?';
        $stmt2 = $conn->prepare($query2);
        $stmt2->execute([$user_id, $friend_id]);
        if($stmt2->rowCount() > 0) {
            throw new PDOException('<p>Friend already in friend list</p>');
            $conn = null;
            return false;
        }
        $query3 = 'INSERT INTO friends VALUES (?, ?, ?)';
        $stmt3 = $conn->prepare($query3);
        $stmt3->execute([$user_id, $friend_id, $friendName]);
        if($stmt3->rowCount() == 1) {
            $conn = null;
            return true;
        } else {    
            $conn = null;
            return false;
        }        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function removeFriend($user_id, $friend_id) {
    $conn = db_connect1();
    $query = 'SELECT * FROM friends WHERE user_id = ? AND friend_id = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id, $friend_id]);
        if($stmt->rowCount() == 0) {
            throw new PDOException('<p>Friend does not exist in friend list.</p>');
            $conn = null;
            return false;
        } 
        $query2 = 'DELETE FROM friends WHERE user_id = ? AND friend_id = ?';
        $stmt2 = $conn->prepare($query2);
        $stmt2->execute([$user_id, $friend_id]);
        if($stmt->rowCount() == 1) {
            $conn = null;
            return true;
        } else {    
            $conn = null;
            return false;
        }        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function showFriends($user_id) {
    $conn = db_connect1();
    $query = 'SELECT friend_id, friendname FROM friends where user_id = ?';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id]);
        if($stmt->rowCount() == 0) {
            throw new PDOException('<p>No friends in your friend list.</p>');
            $conn = null;
            return false;
        } 
        $row = $stmt->fetchall();
        return $row;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>