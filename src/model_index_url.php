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
	if($protection->rowCount() > 0) { // la requete a renvoyé quelque chose
        return True;
    }
    else { // la requete n'a rien renvoyé
    	return False;
    }
}