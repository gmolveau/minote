<?php

/**
 * get the content of the note
 * @param string $url 
 * @return text if PDO successed
 * @return error message if exception catched during PDO
 */
function getContent($url){
	global $pdo;
	$stmt = $pdo->prepare("SELECT content from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return $result['content'];
}

/**
 * check if the note view is protected
 * @param string $url 
 * @return boolean true, if protected otherwise false
 * @return error message if exception catched during PDO
 */
function isViewProtected($url){
	global $pdo;
	$stmt = $pdo->prepare("SELECT pwdView from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return (empty($pwdView));
}

/**
 * check if password entered matches DB
 * @param string $url 
 * @param string $pwd 
 * @return boolean true, if password matches
 * @return error message if exception catched during PDO
 */
function verifyPassword($url,$pwd){
	global $pdo;
	$stmt = $pdo->prepare("SELECT pwdView from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return password_verify($pwd,$result['pwdView']);
}

function isSaved($url){
	global $pdo;
	$stmt=$pdo->prepare("SELECT id from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return (empty($result));
}

function protectView($url,$password){
	global $pdo;
	$hash = password_hash($password, PASSWORD_DEFAULT);
	if (!isSaved($url)){
		$stmt=$pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
		$stmt->bindParam(':url', $url);
		$stmt->bindParam(':content', null);
		$stmt->bindParam(':pwdView', $hash);
		$stmt->bindParam(':pwdEdit', null);
		$stmt->execute();
	}
	else{
		$stmt=$pdo->prepare("UPDATE note SET pwdView = :pwdView WHERE id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->bindParam(':pwdView', $hash);
		$stmt->execute();
	}
}