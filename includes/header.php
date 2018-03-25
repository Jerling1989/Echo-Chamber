<?php
	// REQUIRE CONFIG.PHP (CONNECTION FILE)
	require 'config/config.php';

	// CHECK IF USER IS SIGNED IN
	if(isset($_SESSION['username'])) {
		$userLoggedIn = $_SESSION['username'];
		// IF NOT SIGNED IN REDIRECT USER TO LOGIN PAGE
	} else {
		header('Location: register.php');
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Echo Chamber</title>
</head>
<body>

