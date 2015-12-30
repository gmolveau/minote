<?php

//Model for note edition at 

function isEditProtected($url){
	global $pdo;
	$stmt=$pdo->prepare("SELECT pwdEdit from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return (empty($result));
}

function protectEdit($url,$password){
	global $pdo;
	$hash = password_hash($password, PASSWORD_DEFAULT);
	if !isSaved($url){
		$stmt=$pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
		$stmt->bindParam(':url', $url);
		$stmt->bindParam(':content', null);
		$stmt->bindParam(':pwdView', null);
		$stmt->bindParam(':pwdEdit', $hash);
		$stmt->execute();
	}
	else{
		$stmt=$pdo->prepare("UPDATE note SET pwdEdit = :pwdEdit WHERE id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->bindParam(':pwdEdit', $hash);
		$stmt->execute();
	}
}

function protectView($url,$password){
	global $pdo;
	$hash = password_hash($password, PASSWORD_DEFAULT);
	if !isSaved($url){
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

function verifyPassword($url,$pwd){
	global $pdo;
	$stmt=$pdo->prepare("SELECT pwdEdit from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return password_verify($pwd,$result['pwdEdit'])
}

function getContent($url){
	global $pdo;
	$stmt=$pdo->prepare("SELECT content from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return $result['content'];
}

function updateNote($url,$content){
	global $pdo;
	if !isSaved($url){
		$stmt=$pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
		$stmt->bindParam(':url', $url);
		$stmt->bindParam(':content', $content);
		$stmt->bindParam(':pwdView', null);
		$stmt->bindParam(':pwdEdit', null);
		$stmt->execute();
	}
	else{
		$stmt=$pdo->prepare("UPDATE note SET content = :content WHERE id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->bindParam(':content', $content);
		$stmt->execute();
	}
}

function isSaved($url){
	global $pdo;
	$stmt=$pdo->prepare("SELECT id from note where id = :url");
	$stmt->bindParam(':url', $url);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	return (empty($result));
}

function changeUrl($url,$new_url){
	require './model_index.php';
	if (checkUrl($new_url) ){
		global $pdo;
		$stmt = $pdo->prepare("UPDATE note SET id = :new_url WHERE id = :url");
		$stmt->bindParam(':new_url', $new_url);
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		return True;
	}
	else{
		return False;
	}

}