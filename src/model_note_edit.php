<?php
/**
 * check if the note edition is protected
 * @param string $url 
 * @return boolean true, if protected otherwise false
 * @return error message if exception catched during PDO
 */
function isEditProtected($url)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT pwdEdit from note where id = :url");
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (!empty($result['pwdEdit']));
    }
    catch (PDOException $e) {
        throw ($e);
    }
}
/**
 * check if the note view is protected
 * @param string $url 
 * @param string $password 
 * @return boolean true, if protected otherwise false
 * @return error message if exception catched during PDO
 */
function protectEdit($url, $password)
{
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if (!isSaved($url)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->bindValue(':content', null);
            $stmt->bindValue(':pwdView', null);
            $stmt->bindValue(':pwdEdit', $hash, PDO::PARAM_STR);
            $stmt->execute();
            return True;
        }
        catch (PDOException $e) {
            throw ($e);
        }
    } else {
        try {
            if($password==null or $password==""){
                $stmt = $pdo->prepare("UPDATE note SET pwdEdit = :pwdEdit WHERE id = :url");
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->bindValue(':pwdEdit', null);
                $stmt->execute();
                return true;
            }
            else{
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE note SET pwdEdit = :pwdEdit WHERE id = :url");
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->bindValue(':pwdEdit', $hash, PDO::PARAM_STR);
                $stmt->execute();
                return true;
            }
        }
        catch (PDOException $e) {
            throw ($e);
        }
    }
    
    
}

/**
 * add a password to the view of the note
 * @param string $url 
 * @param string $password 
 * @return boolean true, if success
 * @return error message if exception catched during PDO
 */
function protectView($url, $password)
{
    global $pdo;
    
    if (!isSaved($url)) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->bindValue(':content', null);
            $stmt->bindValue(':pwdView', $hash, PDO::PARAM_STR);
            $stmt->bindValue(':pwdEdit', null);
            $stmt->execute();
            return True;
        }
        catch (PDOException $e) {
            throw ($e);
        }
    } else {
        try {
            if($password==null or $password==""){
                $stmt = $pdo->prepare("UPDATE note SET pwdView = :pwdView WHERE id = :url");
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->bindValue(':pwdView', '', PDO::PARAM_STR);
                $stmt->execute();
                return true;
            }
            else{
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE note SET pwdView = :pwdView WHERE id = :url");
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->bindValue(':pwdView', $hash, PDO::PARAM_STR);
                $stmt->execute();
                return true;
            }
        }
        catch (PDOException $e) {
            throw ($e);
        }
    }
}

/**
 * check if password entered matches DB
 * @param string $url 
 * @param string $pwd 
 * @return boolean true, if password matches
 * @return error message if exception catched during PDO
 */
function verifyPassword($url, $pwd)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT pwdEdit from note where id = :url");
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return password_verify($pwd, $result['pwdEdit']);
    }
    catch (PDOException $e) {
        throw ($e);
    }
}

/**
 * get the content of the note
 * @param string $url 
 * @return text if PDO successed
 * @return error message if exception catched during PDO
 */
function getContent($url)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT content from note where id = :url");
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['content'];
    }
    catch (PDOException $e) {
        throw ($e);
    }
}

/**
 * update the content of the note in the DB
 * @param string $url 
 * @param text $content 
 * @return boolean true, if PDO success
 * @return error message if exception catched during PDO
 */
function updateNote($url, $content)
{
    global $pdo;
    if (!isSaved($url)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':pwdView', null);
            $stmt->bindValue(':pwdEdit', null);
            $stmt->execute();
            return True;
        }
        catch (PDOException $e) {
            throw ($e);
        }
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE note SET content = :content WHERE id = :url");
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->execute();
            return True;
        }
        catch (PDOException $e) {
            throw ($e);
        }
    }
}

/**
 * check if database is saved in the DB
 * @param string $url 
 * @return boolean true if saved in DB, false otherwise
 * @return error message if exception catched during PDO
 */
function isSaved($url)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id from note where id = :url");
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (!empty($result) or !empty($result['id']));
    }
    catch (PDOException $e) {
        throw ($e);
    }
}

/**
 * change the url of the note
 * @param string $url 
 * @param string $new_url 
 * @return boolean true, if PDO success, false if new URL is invalid or taken
 * @return error message if exception catched during PDO
 */
function checkUrl($url)
{
    return (strlen($url) < 10 and ctype_alnum($url)); //si url < 10 caracteres et si elle est alphanumeric
}
function changeUrl($url, $new_url)
{
    if (checkUrl($new_url)) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("UPDATE note SET id = :new_url WHERE id = :url");
            $stmt->bindValue(':new_url', $new_url, PDO::PARAM_STR);
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
        catch (PDOException $e) {
            throw ($e);
        }
    } else {
        return false;
    }
}
