<?php
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
	$protection = $pdo->query("SELECT `id` FROM `note` WHERE `id` = '$url'");
	if($protection->rowCount() > 0) {
        return True;
    }
    else {
    	return False;
    }
}