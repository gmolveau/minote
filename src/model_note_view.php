<?php

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
 * check if the note view is protected
 * @param string $url 
 * @return boolean true, if protected otherwise false
 * @return error message if exception catched during PDO
 */
function isViewProtected($url)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT pwdView from note where id = :url");
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (!empty($result['pwdView']));
    }
    catch (PDOException $e) {
        throw ($e);
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
        $stmt = $pdo->prepare("SELECT pwdView from note where id = :url");
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return password_verify($pwd, $result['pwdView']);
    }
    catch (PDOException $e) {
        throw ($e);
    }
}

/**
 * check if the note is registered in the DB
 * @param string $url 
 * @return boolean true, if note is registered, false if not
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
 * add a password to protect the view
 * @param string $url 
 * @param string $password 
 * @return boolean true, if password was added
 * @return error message if exception catched during PDO
 */
function protectView($url, $password)
{
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if (!isSaved($url)) {
        try {
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
            $stmt = $pdo->prepare("UPDATE note SET pwdView = :pwdView WHERE id = :url");
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->bindValue(':pwdView', $hash, PDO::PARAM_STR);
            $stmt->execute();
            return True;
        }
        catch (PDOException $e) {
            throw ($e);
        }
    }
}