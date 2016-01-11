<?php

/**
 * get the content of the note
 * @param string $url 
 * @return text if PDO successed
 * @return error message if exception catched during PDO
 */
function getContent($url,$pdo)
{
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
function isViewProtected($url,$pdo)
{
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
function verifyPassword($url,$password,$pdo)
{
    try {
        require 'password_hash.php';
        $stmt = $pdo->prepare("SELECT pwdView from note where id = :url");
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return validate_password($password, $result['pwdView']);
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
function isSaved($url,$pdo)
{
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
