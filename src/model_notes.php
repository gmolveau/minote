<?php

/**
 * get the id of all the notes
 * @return array
 */
function get_all_notes($pdo){
    try {
	    $all_notes = $pdo->query("SELECT id FROM note;");
	    return $all_notes; //renvoyer à la view
    }
    catch( PDOException $e ) {
    	throw( $e->getMessage( ));
	}
};
?>