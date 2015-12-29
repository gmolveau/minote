<?php

function getContent($url){
	global $pdo;
	$recup = $pdo->query("SELECT `content` FROM `note` WHERE `id` = '$url'");
	return $recup['content'];
}

function isViewProtected($url){
	global $pdo;
	$editProt = $pdo->query("SELECT pwdEdit FROM note WHERE id = $url");
	return ($editProt->rowCount()>0);
}

function verifyPassword($url,$pwd){
	global $pdo;
	$recup = $pdo->query("SELECT `pwdView` FROM `note` WHERE `id` = '$url'");
	return password_verify($pwd,$recup['pwdView']);
}