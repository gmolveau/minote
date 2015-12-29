<?php

function importNoteTwo($url){
	global $pdo;
	$recup = $pdo->query("SELECT content FROM note WHERE id = $url");
	return $recup['content'];
}

function viewProtectedTwo($url){
	global $pdo;
	$viewProt = $pdo->query("SELECT pwdView FROM note WHERE id = $url");
	if($viewProt->rowCount()>0){
		return True; 
	}
	else{
		return False;
	}
}

function verifPwdView($url,$pwd){
	global $pdo;
	$recup = $pdo->query("SELECT pwdView FROM note WHERE id=$url");
	if($pwd==$recup['pwdEdit']){
		return True;
	}
	else{
		return False;
	}
}