<?php

function fetch_user_ids($usernames) {
    $conn = db_connect1();
    $inQuery = implode(',', array_fill(0, count($usernames), '?'));
    $query =  "SELECT user_id, username FROM users WHERE username IN (" . $inQuery . ")";
    try {
        $stmt = $conn->prepare($query);
        foreach($usernames as $key => $value) {
            $stmt->bindValue(($key+1), $value);
        }
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $names = array();
            while(($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $names[$row['username']] = $row['user_id'];
            }
            return $names;
        } else {
            throw new PDOException('<p>None of the names match our users</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function create_conversation($user_ids, $user_id, $subject, $body) {
    $conn = db_connect1();
    $query = 'INSERT INTO conversations VALUES (?, ?)';
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([null, $subject]);
        $conversation_id = $conn->lastInsertId();
        $query2 = "INSERT INTO conversations_messages VALUES (?, ?, ?, null, ?)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->execute([null, $conversation_id, $user_id, $body]);
        
        $values = array("({$conversation_id}, {$user_id}, 0, 0)");
        foreach($user_ids as $user) {
            $user = (int)$user;
            $values[] = "({$conversation_id}, {$user}, 0, 0)";
        }
        $query3 ="INSERT INTO conversations_members VALUES" .implode( ", ", $values);
        $stmt3 = $conn->prepare($query3);
        $stmt3->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} 
    
function fetch_conversation_summary($user_id) {
    $conn = db_connect1();
    $query = "SELECT c.conversation_id, c.conversation_subject,
        max(cm.conv_mess_date) 
        AS conversation_last_reply, MAX(cm.conv_mess_date) > cmem.conversation_last_view 
        AS conversation_unread FROM conversations AS c, conversations_messages AS cm, conversations_members AS cmem 
        WHERE c.conversation_id = cm.conversation_id 
        AND cm.conversation_id = cmem.conversation_id 
        AND cmem.user_id = ? AND cmem.conversation_deleted = ? 
        GROUP BY c.conversation_id ORDER BY conversation_last_reply DESC";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id, 0]);
        $conversations = array();

        while(($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $conversations[] = array(
                'id' => $row['conversation_id'],
                'subject' => $row['conversation_subject'],
                'last_reply' => $row['conversation_last_reply'],
                'unread' => ($row['conversation_unread'] == 1)
            );
        }
        return $conversations;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function validate_conversation_id($conversation_id, $user_id) {
    $conversation_id = (int)$conversation_id;
    $conn = db_connect1();
    $query = "SELECT COUNT(1) FROM conversations_members WHERE conversation_id = ? AND user_id = ? AND conversation_deleted = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$conversation_id, $user_id, 0]);
        if($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_NUM);
            return ($result[0] == 1);
        } else {
            throw new PDOException('<p>Error in returning results</p>');
            $conn = null;
            return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function delete_conversation($conversation_id, $user_id) {
    $conversation_id = (int)$conversation_id;
    $conn = db_connect1();
    $query = "SELECT DISTINCT conversation_deleted FROM conversations_members WHERE user_id != ? AND conversation_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id, $conversation_id]);
        if($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_NUM);
            if($stmt->rowCount() == 1 && $result[0] == 1) {
                $stmt2 = $conn->prepare("DELETE FROM conversations WHERE conversation_id = ?");
                $stmt3 = $conn->prepare("DELETE FROM conversations_messages where conversation_id = ?");
                $stmt4 = $conn->prepare("DELETE from conversations_members where conversation_id = ?");
                $stmt2->execute([$conversation_id]);
                $stmt3->execute([$conversation_id]);
                $stmt4->execute([$conversation_id]);
                $conn = null;
                return true;
            } else {
                $query5 = "UPDATE conversations_members SET conversation_deleted = 1 WHERE conversation_id = ? AND user_id = ?";
                $stmt5 = $conn->prepare($query5);
                $stmt5->execute([$conversation_id, $user_id]);
                $conn = null;
                return true;
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function fetch_conversation_messages( $user_id, $conversation_id ) {
    $conversation_id = (int)$conversation_id;
    $conn = db_connect1();
    $query = "SELECT cm.conv_mess_date, cm.conv_mess_date > cmem.conversation_last_view 
        AS message_unread, cm.conv_mess_text, u.user_id, u.username 
        FROM conversations_messages AS cm, conversations_members AS cmem, users AS u WHERE cm.user_id = u.user_id 
        AND cm.conversation_id = cmem.conversation_id 
        AND cmem.user_id = ? AND cm.conversation_id = ? 
        ORDER BY cm.conv_mess_date desc";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id, $conversation_id]);
        if($stmt->rowCount() > 0) {
            $messages = array();
            while(($row  = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $messages[] = array(
                    'date' => $row['conv_mess_date'],
                    'unread' => $row['message_unread'],
                    'text' => $row['conv_mess_text'],
                    'username' => $row['username'],
                    'user_id' => $row['user_id']
                );
            }
            return $messages;
        } else {
            throw new PDOException('<p>No messages to display</p>');
            $conn = null;
            return false;
        } 
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
    
function update_conversation_last_view($conversation_id, $user_id) {
    $conversation_id = (int)$conversation_id;
    $conn = db_connect1();
    $query = "UPDATE conversations_members SET 
            conversation_last_view = NULL WHERE conversation_id = ? 
            AND user_id = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$conversation_id, $user_id]);
        if($stmt->rowCount() > 0) {
            $conn = null;
            return TRUE;
        } else {
            throw new PDOException('<p>Conversation could not be updated.</p>');
            $conn = null;
            return FALSE;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function add_conversation_message($conversation_id, $user_id, $text) {
    $conversation_id = (int)$conversation_id;
    $conn = db_connect1();
    $query = "INSERT INTO conversations_messages VALUES (?, ?, ?, null, ?)";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([null, $conversation_id, $user_id, $text]);
        if($stmt->rowCount() > 0 ) {
            $query2 = "UPDATE conversations_members SET conversation_deleted = 0 WHERE conversation_id = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->execute([$conversation_id]);
            if($stmt2->rowCount() > 0) {
                $conn = null;
                return TRUE;
            } else {
                // throw new PDOException('<p>Conversation could not be added.</p>');
                $conn = null;
                return FALSE;
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>