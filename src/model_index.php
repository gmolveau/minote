<?php
//model for blank URL at index milinks.info/ 

/**
 * generate a valid and not taken URL
 * @return string $url if success, call generateUrl (recursif) if url taken
 */
function generateUrl(){
	global $pdo; // get PDO connection
	// generate a <10 characters (numerical and alphabetical) string, not present in the DB ex: ajf63
	$pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$url = '';
	for ($i = 0; $i < 7; $i++) {
	    $url .= $pool[rand(0, strlen($pool) - 1)];
	}
	try {
		$stmt = $pdo->prepare("SELECT * from note where id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		$result=$stmt->fetch(PDO::FETCH_ASSOC);
	} 	
	catch( PDOException $e ) {
    	throw( $e->getMessage( ));
	}
	if(empty($result)) { // la requete n'a rien renvoyé donc url pas prise
		return $url;
    }
    else {
        generateUrl();	
    }
}

//model for milinks.info/{url}
/**
 * check if the url is valid and not taken
 * @param string $url 
 * @return boolean true if valid and not taken, false otherwise
 */
function checkUrl($url){
	if ( strlen($url) < 10 and ctype_alnum($url) ) { //si url < 10 caracteres et si elle est alphanumeric
		global $pdo; // get PDO connection
		try {
			$stmt = $pdo->prepare("SELECT * from note where id = :url");
			$stmt->bindParam(':url', $url);
			$stmt->execute();
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			return (empty($result)); // la requete n'a rien renvoyé donc url est conforme et pas prise	
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
	else {
		return False;
	}
}

