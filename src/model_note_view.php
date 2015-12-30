<?php

function getContent($url){
	global $pdo;
	$stmt = $pdo->prepare("SELECT content from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return $result['content'];
}

function isViewProtected($url){
	global $pdo;
	$stmt = $pdo->prepare("SELECT pwdView from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return (empty($pwdView));
}

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