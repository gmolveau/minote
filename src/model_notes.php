<?php
function get_all_notes(){
    global $pdo; // get PDO connection
    $all_notes = $pdo->query("SELECT id FROM note;");
    return $all_notes; //renvoyer à la view
};
?>