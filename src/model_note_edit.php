<?php

//Model for note edition at 

function editProtectedTwo($url){
	global $pdo;
	$editProt = $pdo->query("SELECT pwdEdit FROM note WHERE id = $url");
	if(!is_Null($editProt['pwdEdit'])){
		return True; 
	}
	else{
		return False;
	}
}

function VerifPwd($url,$pwd){
	global $pdo;
	$recup = $pdo->query("SELECT pwdEdit FROM note WHERE id=$url");
	if($pwd==$recup['pwdEdit']){
		return True;
	}
	else{
		return False;
	}
}

function ImportNote($url){
	global $pdo;
	$recup = $pdo->query("SELECT content FROM note WHERE id = $url");
	return $recup['content'];
}

function UpdateNote($url,$cont){
	global $pdo;
	$upd = $pdo->prepare("UPDATE note SET content=:contenu WHERE id=:url")
	$upd->execute(array(
				'content' => $content,
				'url'=>$url,
				));
}