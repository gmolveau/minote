<?php
/**
 * check if the note edition is protected
 * @param string $url 
 * @return boolean true, if protected otherwise false
 * @return error message if exception catched during PDO
 */
function isEditProtected($url){
	global $pdo;
	try{
		$stmt=$pdo->prepare("SELECT pwdEdit from note where id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		$result=$stmt->fetch(PDO::FETCH_ASSOC);
		return (empty($result));
	}
	catch( PDOException $e ) {
    	throw( $e->getMessage( ));
	}
}
/**
 * check if the note view is protected
 * @param string $url 
 * @param string $password 
 * @return boolean true, if protected otherwise false
 * @return error message if exception catched during PDO
 */
function protectEdit($url,$password){
	global $pdo;
	$hash = password_hash($password, PASSWORD_DEFAULT);
	if(!isSaved($url)){
		try{
			$stmt=$pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
			$stmt->bindParam(':url', $url);
			$stmt->bindParam(':content', null);
			$stmt->bindParam(':pwdView', null);
			$stmt->bindParam(':pwdEdit', $hash);
			$stmt->execute();
			return True;
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
	else{
		try{
			$stmt=$pdo->prepare("UPDATE note SET pwdEdit = :pwdEdit WHERE id = :url");
			$stmt->bindParam(':url', $url);
			$stmt->bindParam(':pwdEdit', $hash);
			$stmt->execute();
			return True;
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
}

/**
 * add a password to the view of the note
 * @param string $url 
 * @param string $password 
 * @return boolean true, if success
 * @return error message if exception catched during PDO
 */
function protectView($url,$password){
	global $pdo;
	$hash = password_hash($password, PASSWORD_DEFAULT);
	if ( !isSaved($url) ){
		try{
			$stmt=$pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
			$stmt->bindParam(':url', $url);
			$stmt->bindParam(':content', null);
			$stmt->bindParam(':pwdView', $hash);
			$stmt->bindParam(':pwdEdit', null);
			$stmt->execute();
			return True;
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
	else{
		try{
			$stmt=$pdo->prepare("UPDATE note SET pwdView = :pwdView WHERE id = :url");
			$stmt->bindParam(':url', $url);
			$stmt->bindParam(':pwdView', $hash);
			$stmt->execute();
			return True;
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
}

/**
 * check if password entered matches DB
 * @param string $url 
 * @param string $pwd 
 * @return boolean true, if password matches
 * @return error message if exception catched during PDO
 */
function verifyPassword($url,$pwd){
	global $pdo;
	try{
		$stmt=$pdo->prepare("SELECT pwdEdit from note where id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		$result=$stmt->fetch(PDO::FETCH_ASSOC);
		return password_verify($pwd,$result['pwdEdit']);
	}
	catch( PDOException $e ) {
    	throw( $e->getMessage( ));
	}
}

/**
 * get the content of the note
 * @param string $url 
 * @return text if PDO successed
 * @return error message if exception catched during PDO
 */
function getContent($url){
	global $pdo;
	try{
		$stmt=$pdo->prepare("SELECT content from note where id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		$result=$stmt->fetch(PDO::FETCH_ASSOC);
		return $result['content'];
	}catch( PDOException $e ) {
    	throw( $e->getMessage( ));
	}
}

/**
 * update the content of the note in the DB
 * @param string $url 
 * @param text $content 
 * @return boolean true, if PDO success
 * @return error message if exception catched during PDO
 */
function updateNote($url,$content){
	global $pdo;
	if !isSaved($url){
		try{
			$stmt=$pdo->prepare("INSERT INTO note(id,content,pwdView,pwdEdit) VALUES(:url,:content,:pwdView,:pwdEdit)");
			$stmt->bindParam(':url', $url);
			$stmt->bindParam(':content', $content);
			$stmt->bindParam(':pwdView', null);
			$stmt->bindParam(':pwdEdit', null);
			$stmt->execute();
			return True;
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
	else{
		try{
			$stmt=$pdo->prepare("UPDATE note SET content = :content WHERE id = :url");
			$stmt->bindParam(':url', $url);
			$stmt->bindParam(':content', $content);
			$stmt->execute();
			return True;
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
}

/**
 * check if database is saved in the DB
 * @param string $url 
 * @return boolean true if saved in DB, false otherwise
 * @return error message if exception catched during PDO
 */
function isSaved($url){
	global $pdo;
	try{
		$stmt=$pdo->prepare("SELECT id from note where id = :url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		$result=$stmt->fetch(PDO::FETCH_ASSOC);
		return (!empty($result));
	}
	catch( PDOException $e ) {
    	throw( $e->getMessage( ));
	}
}

/**
 * change the url of the note
 * @param string $url 
 * @param string $new_url 
 * @return boolean true, if PDO success, false if new URL is invalid or taken
 * @return error message if exception catched during PDO
 */
function changeUrl($url,$new_url){
	require './model_index.php';
	if (checkUrl($new_url) ){
		global $pdo;
		try{
			$stmt = $pdo->prepare("UPDATE note SET id = :new_url WHERE id = :url");
			$stmt->bindParam(':new_url', $new_url);
			$stmt->bindParam(':url', $url);
			$stmt->execute();
			return True;
		}
		catch( PDOException $e ) {
    		throw( $e->getMessage( ));
		}
	}
	else{
		return False;
	}
}