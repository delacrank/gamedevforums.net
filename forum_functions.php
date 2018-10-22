<?php
    
function getPostsByCat($Id) {
    $conn = db_connect1();
    $query = "SELECT cat_title, post_id, posts.cat_id, post_title, description FROM posts, categories WHERE posts.cat_id = categories.cat_id AND posts.cat_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$Id]);
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetchall();
            $stmt = null;
            return $row;
        } else {
            throw new PDOException('<p>There are no Posts to discuss.</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getPostsByPost($Id) {
    $conn = db_connect1();
    $query = "SELECT p.post_title, p.description, p.date, p.user_id, u.username FROM posts AS p, users AS u WHERE p.user_id = u.user_id AND post_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$Id]);
        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $conn = null;
            return $row;
        } else {
            throw new PDOException('<p>There are no Posts to discuss.</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getCategories() {
    $conn = db_connect1();
    $query = "SELECT cat_id, cat_title FROM categories";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetchall();
            $conn = null;
            return $row;
        } else {
            throw new PDOException('<p>There are no Categories.</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function create_post($title, $desc, $catid, $userid) {
    $conn = db_connect1();
    $query = "SELECT post_title, description FROM posts WHERE post_title = ? AND description = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$title, $desc]);
        if($stmt->rowCount() > 0) {
            throw new PDOException('<p>Error Duplicate posts, please create a new title or description.</p>');
            $conn = null;
            return false;
        } else {
            $query2 = "INSERT INTO posts VALUES(default, ?, ?, ?, ?, default)";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([$catid, $userid, $title, $desc]);
            if($stmt2->rowCount() == 1) {
                header('Location: index.php?page=display_messages&post_id='. $conn->lastInsertId().'&cat_id='. htmlentities($catid));
            } else {
                throw new PDOException('<br><br><p>Something went wrong with trying to create your post, please try again.</p>');   
                $conn = null;
                return false;
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function create_message($user_id, $postid, $catid, $message) {
    $conn = db_connect1();
    $query = "INSERT INTO messages VALUES (null, ?, ?, ?, ?, null)";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id, $postid, $catid, $message]);
        if($stmt->rowCount() == 1) {
            header('Location: index.php?page=display_messages&post_id='. htmlentities($postid).'&cat_id='. htmlentities($catid));
        } else {
            throw new PDOException('<br><br><p>Something went wrong with trying to create message, please try again.</p>');   
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getMessagesForum() {
    $conn = db_connect1();
    $query = "SELECT DISTINCT c.cat_title, p.cat_id, p.post_id, p.post_title, p.description, u.username, u.user_id 
    FROM messages AS m, users AS u, categories AS c, posts AS p 
    WHERE p.user_id = u.user_id 
    AND p.cat_id = c.cat_id ORDER BY m.date DESC LIMIT 10";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetchall();
            $conn = null;
            return $row;
        } else {
            throw new PDOException('<p>There are no posts to discuss.</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// display messages
function getMessages($id) {
    $conn = db_connect1();
    $query = "SELECT m.mess_id, m.user_id,  m.message_txt, m.date, m.post_id,       p.post_title, u.username 
        FROM messages AS m, users AS u, posts AS p 
        WHERE m.user_id = u.user_id AND m.post_id = p.post_id 
        AND m.post_id = ? ORDER BY m.date";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetchall();
            $conn = null;
            return $row;
        } else {
            throw new PDOException('<p>There are no posts to discuss.</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// get ip address
function getIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// store user ip
function storeIp($userip, $catid, $postid) {
    $conn = db_connect1();
    $query = "SELECT * FROM views WHERE user_ip = ? 
            AND cat_id = ? AND post_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$userip, $catid, $postid]);
        if($stmt->rowCount() != 0) {
            $conn = null;
            return false;
        }
        $query2 = "INSERT INTO views VALUES (default, ?, ?, ?)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->execute([$postid, $catid, $userip]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// display view count
function display_views($catid, $postid) {
    $conn = db_connect1();
    $query = "SELECT * FROM views WHERE cat_id = ? AND post_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$catid, $postid]);
        $amount = $stmt->rowCount();
        return $amount;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// display reply count
function display_replies($catId, $postId) {
    $conn = db_connect1();
    $query = "SELECT * FROM messages WHERE cat_id = ? AND post_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$catId, $postId]);
        $amount = $stmt->rowCount();
        return $amount;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>