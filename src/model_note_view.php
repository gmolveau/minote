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