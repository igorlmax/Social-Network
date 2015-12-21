<?php
/*
 * DB Connection
 */

$connection = mysqli_connect("localhost","webadmin","passadmin","social_network");

if(mysqli_connect_error()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$dbhost = 'localhost';
$dbname = 'social_network';
$dbuser = 'webadmin';
$dbpass = 'passadmin';
$appname = 'Social Network';

/*
$connection = new mysqli($dbhost, $dbname, $dbpass, $dbuser);
if ($connection->connect_error) die($connection->connect_error);
*/

/*
 * Create App Functions()
 */   
function createTable($name, $query){
	queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
	echo "Table '$name' created or alreadt exists.<br>";
}

function queryMysql($query){
	global $connection;
	$result = $connection->query($query);
	if (!$result) die ($connection->error);
	return $result;
}

function destroySession(){
	$_SESSION = array();
	
	if (session_id() != "" || isset($_COOKIE[session_name()]))
	 setcookie(session_name(), '', time()-2592000, '/');
	 
	 session_destroy();
}

function sanitizeString($var){
	global $connection;
	
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var - stripslashes($var);
	return $connection->real_escape_string($var);
}

function showProfile($user){
	if (file_exists("$user.jpg"))
	echo "<img src='$user.jpg' style='float:left;'>";
	
	$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
	
	if ($result->num_rows){
		$row = $result->fetch_array(MYSQLI_ASSOC);
		echo stripslashes($row['text']). "<br style='clear:left;'><br>";
	}
}
?>
  </body>
</html>