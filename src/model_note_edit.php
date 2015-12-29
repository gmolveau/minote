<?php

//Model for note edition at 

function isEditProtected($url){
	global $pdo;
	$editProt = $pdo->query("SELECT pwdEdit FROM note WHERE id = $url");
	return ($editProt->rowCount()>0);
}

function protectEdit($url,$password){
	global $pdo;
	$upd = $pdo->prepare("INSERT INTO note (SET `content`=:contenu WHERE `id`=:url");
	$upd->execute(array(
				'content' => $content,
				'url'=>$url,
				));
}

function protectView($url,$password){
	global $pdo;
	$upd = $pdo->prepare("INSERT INTO note (SET `content`=:contenu WHERE `id`=:url");
	$upd->execute(array(
				'content' => $content,
				'url'=>$url,
				));
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
	$upd = $pdo->prepare("UPDATE `note` SET `content`=:contenu WHERE `id`=:url");
	$upd->execute(array(
				'content' => $content,
				'url'=>$url,
				));
}

