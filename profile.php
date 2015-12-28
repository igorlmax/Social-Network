<?php
/*
 * User's profile
 */

//Get the Header
require_once 'header.php';

// Check if the user is logged in
if(!$loggedin) die();

// If user is logged in, display the profile
echo "<div class='main'><h3>Your Profile</h3>";

// Check to see whether some text was posted
$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

// IF there is already text, UPDATE it, otherwise INSERT the new one
if (isset($_POST['text'])){
	$text = sanitizeString($_POST['text']);
	$text = preg_replace('/\s\s+/', ' ', $text);

	if ($result->num_rows)
	 queryMysql("UPDATE profiles SET text='$text' where user='$user'");
	else queryMysql("INSERT INTO profiles VALUES('$user', '$text')");
}

else {
	if ($result->num_rows) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$text = stripslashes($row['text']);
	} else $text = "";
}

$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

// Check to see whether some image file was uploaded
if(isset($_FILES['image']['name'])){
	$saveto = "$user.jpg";
	move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
	$typeok = TRUE;

// examine and accept only if it is a jpeg, png or gif image
	switch ($_FILES['image']['type']){
		case "image/gif": $src = imagecreatefromgif($saveto); break;
		case "image/jpeg": // Both regular and progressive jpegs
		case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
		case "image/png": $src = imagecreatefrompng($saveto); break;
		default:
	}

// Image’s dimensions store in $w and $h	
	if ($typeok){
		list($w, $h) = getimagesize($saveto);

		$max = 100;
		$tw = $w;
		$th = $h;

		if ($w > $h && $max < $w)
		{
			$th = $max / $w * $h;
			$tw = $max;
		}
		elseif ($h > $w && $max < $h)
		{
			$tw = $max / $h * $w;
			$th = $max;
		}
		elseif ($max < $w)
		{
			$tw = $th = $max;
		}

		$tmp = imagecreatetruecolor($tw, $th);
// Resample the image from $src, to the new $tmp
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
		
// Sharpen the image
		imageconvolution($tmp, array(array(-1, -1, -1),
		array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
		imagejpeg($tmp, $saveto);
		
//remove both the original and the resized image canvases from memory using
		imagedestroy($tmp);
		imagedestroy($src);
	}
}
// showProfile function from functions.php output the form HTML
showProfile($user);

/*
 * enctype='multipart/form-data' allows us to
 * send more than one type of data at a time
 */
echo <<<_END
  <form method='post' action='profile.php' enctype='multipart/form-data'>
  <h3>Enter or edit your details and/or upload an image</h3>
  <textarea name='text' cols='50' rows='3'>$text</textarea><br>
_END;
?>

<!-- input type of file -->
		Image: <input type="file" name='image' size='14'>
		
<!-- After submitting the code above is executed -->
		<input type="submit" value='Save Profile'>
		</form></div><br>
  </body>
</html>