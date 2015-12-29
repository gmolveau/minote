<?php
//model for milinks.info/{url}
function checkUrl($url){
	global $pdo; // get PDO connection
	$validation = $pdo->query("SELECT `id` FROM `note` WHERE `id` = '$url'");
	if($validation->rowCount() > 0) { // la requete a renvoyé quelque chose
		return False;
    }
    else { // la requete n'a rien renvoyé
    	return True;
    }
}

function viewProtected($url){
	global $pdo; // get PDO connection
	$protection = $pdo->query("SELECT `pwdView` FROM `note` WHERE `id` = '$url'");
<<<<<<< HEAD
	if($protection->rowCount() > 0) { // la requete a renvoyé quelque chose
=======
	if(!is_Null($editProt['pwdEdit'])){
>>>>>>> 15562c82bfb54d4ed55359944a098fe8cdd48c9b
        return True;
    }
    else { // la requete n'a rien renvoyé
    	return False;
    }
}

function editProtected($url){
	global $pdo;
	$editProt = $pdo->query("SELECT pwdEdit FROM note WHERE id = $url");
	if(!is_Null($editProt['pwdEdit'])){
		return True; 
	}
	else{
		return False
	}
}
