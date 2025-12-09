<?php
	session_start();
	include_once("includes/languages/" . $_SESSION['lang'] . ".php");
	include_once("header.php");
	include_once('Database/connect.php');
	include_once("session.php");
	
	// Function to translate text
	function t($key) {
	    global $lang;
	    return isset($lang[$key]) ? $lang[$key] : $key;
	}
	
	$q = mysqli_query($con, "SELECT * FROM temp");
	$im = "";
	$nm = "";
	$pri = 0;
	$r = mysqli_num_rows($q);
	
	while($res = mysqli_fetch_array($q)) {
		$id = $res[0];
		$im = $res[1];
		$nm = $res[2];
		$pri = $res[3];
		
		$q1 = mysqli_query($con, "INSERT INTO booking(theme, thm_nm, price) VALUES('$im', '$nm', '$pri')");
		
		if($q1 > 0) {
			echo "<script>alert('" . t('booking_confirmation') . "');</script>";
			echo '<script type="text/javascript">window.location="index.php";</script>';
		} else {
			echo "<script>alert('" . t('booking_failed') . "');</script>";
		}
	}

	include_once("footer.php");
?>