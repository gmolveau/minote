<?php
function get_all_cat(){
// get PDO connection
global $pdo;
$all_cat = $pdo->query("select cat from crise;");
return $all_cat;								//envoyer à la view
};
?>