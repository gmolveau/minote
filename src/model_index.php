<?php
//model for blank URL at index milinks.info/ 
function generateUrl(){
	global $pdo; // get PDO connection
	// generate a <10 characters (numerical and alphabetical) string, not present in the DB ex: ajf63
	$pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$url = '';
	for ($i = 0; $i < 7; $i++) {
	    $url .= $pool[rand(0, strlen($pool) - 1)];
	}
	$validation = $pdo->query("SELECT `id` FROM `note` WHERE `id` = '$url'");
	if($validation->rowCount() > 0) {
		generateUrl();
    }
    else {
        return $url;	
    }
}

//model for milinks.info/{url}
function checkUrl($url){
	global $pdo; // get PDO connection
	$validation = $pdo->query("SELECT `id` FROM `note` WHERE `id` = '$url'");
	if($validation->rowCount() > 0) {
		return False;
    }
    else {
    	return True;
    }
}

function viewProtected($url){
	global $pdo; // get PDO connection
	$protection = $pdo->query("SELECT `pwdView` FROM `note` WHERE `id` = '$url'");
	if(!is_Null($protection['pwdView'])){
        return True;
    }
    else {
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
		return False;
	}
}
