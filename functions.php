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
 * Create App Functions
 */   

// Create table
function createTable($name, $query){
	queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
	echo "Table '$name' created or alreadt exists.<br>";
}

// Issue a Query to MySQL
function queryMysql($query){
	global $connection;
	$result = $connection->query($query);
	if (!$result) die ($connection->error);
	return $result;
}

// Destroy the Session and log users out
function destroySession(){
	$_SESSION = array();
	
	if (session_id() != "" || isset($_COOKIE[session_name()]))
	 setcookie(session_name(), '', time()-2592000, '/');
	 
	 session_destroy();
}

// Removes malicious code
function sanitizeString($var){
	global $connection;
	
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripslashes($var);
	
	return $connection->real_escape_string($var);
}

// Show the User profile image and about me
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