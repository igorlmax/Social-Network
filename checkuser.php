<?php
/*
 * Looks up a username in the database and returns a string
 * indicating whether it has already been taken
 */
require_once 'functions.php';

if (isset ( $_POST ['user'] )) {
	$user = sanitizeString ( $_POST ['user'] );
	$result = queryMysql ( "SELECT * FROM members WHERE user='$user'" );
	
	// If there is such a result in some row
	if ($result->num_rows)
		echo "<span class='taken'>&nbsp;&#x2718; " . "This username is taken</span>";
	else
		echo "<span class='available'>&nbsp;&#x2714; " . "This username is available</span>";
}
?>