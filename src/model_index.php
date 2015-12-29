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
	if ( strlen($url) < 10 and ctype_alnum($url) ) { //si url < 10 caracteres et si elle est alphanumeric
		global $pdo; // get PDO connection
		$validation = $pdo->query("SELECT `id` FROM `note` WHERE `id` = '$url'");
		return ($validation->rowCount() > 0);
	}
	else {
		return False;
	}
}

