<?php
//model for blank URL at index milinks.info/ 

/**
 * generate a valid and not taken URL
 * @return string $url if success, call generateUrl (recursif) if url taken
 */
function generateUrl($pdo,$length = 5,$try = 0)
{
    // generate a <20 characters (numerical and alphabetical) string, not present in the DB ex: ajf63
    if($try > $length*$length){
        $length+=1;
        $try=0;
    }
    $pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $url  = '';
    for ($i = 0; $i < $length; $i++) {
        $url .= $pool[rand(0, strlen($pool) - 1)];
    }
    try {
        $stmt = $pdo->prepare("SELECT id from note where id = :url");
        $stmt->bindParam(':url', $url);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        throw ($e->getMessage());
    }
    if (empty($result)) { // la requete n'a rien renvoyé donc url pas prise
        return $url;
    } else {
        generateUrl($length,$try+1);
    }
}

//model for milinks.info/{url}
/**
 * check if the url is valid and not taken
 * @param string $url 
 * @return boolean true if valid and not taken, false otherwise
 */
function checkUrl($url)
{
    return (strlen($url) < 20 and ctype_alnum($url)); //si url < 10 caracteres et si elle est alphanumeric
}
