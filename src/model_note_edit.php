<?php

//Model for note edition at 

function isEditProtected($url){
	global $pdo;
	$editProt = $pdo->query("SELECT pwdEdit FROM note WHERE id = $url");
	return ($editProt->rowCount()>0);
}

function protectEdit($url,$password){
	global $pdo;
	$hash = password_hash($password, PASSWORD_DEFAULT);
	if !isSaved($url){
		$upd = $pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
		$upd->execute(array(
				'url'=>$url,
				'content' => null,
				'pwdView' => null,				
				'pwdEdit'=> $hash
				));
	}
	else{
		$upd = $pdo->prepare("UPDATE `note` SET `pwdEdit`=:pwdEdit WHERE `id`=:url");
		$upd->execute(array(
				'pwdEdit' => $hash,
				'url'=>$url,
				));
	}
}

function protectView($url,$password){
	global $pdo;
	$hash = password_hash($password, PASSWORD_DEFAULT);
	if !isSaved($url){
		$upd = $pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
		$upd->execute(array(
				'url'=>$url,
				'content' => null,
				'pwdView' => $hash,
				'pwdEdit'=> null,
				));
	}
	else{
		$upd = $pdo->prepare("UPDATE `note` SET `pwdView`=:pwdView WHERE `id`=:url");
		$upd->execute(array(
				'pwdView' => $hash,
				'url'=>$url,
				));
	}
}

function verifyPassword($url,$pwd){
	global $pdo;
	$recup = $pdo->query("SELECT `pwEdit` FROM `note` WHERE `id` = '$url'");
	return password_verify($pwd,$recup['pwdEdit'])
}

function getContent($url){
	global $pdo;
	$recup = $pdo->query("SELECT `content` FROM `note` WHERE `id` = '$url'");
	return $recup['content'];
}

function updateNote($url,$content){
	global $pdo;
	$upd = $pdo->prepare("UPDATE `note` SET `content`=:content WHERE `id`=:url");
	$upd->execute(array(
				'content' => $content,
				'url'=>$url,
				));
}

function isSaved($url){
	global $pdo;
	$validation = $pdo->query("SELECT `id` FROM `note` WHERE `id` = '$url'");
	return ($validation->rowCount() > 0);
}

function changeUrl($url,$new_url){
	require './model_index.php';
	if (checkUrl($new_url) ){
		global $pdo;
		$upd = $pdo->prepare("UPDATE `note` SET `id`=:new_url WHERE `id`=:url");
		$upd->execute(array(
				'new_url' => $new_url,
				'url'=>$url,
				));
		return True;
	}
	else{
		return False;
	}

}