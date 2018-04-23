<?php

	// TURN ON OUTPUT BUFFERING
	ob_start();
	// START SESSION
	session_start();

	// SET DEFAULT TIMEZONE
	$timezone = date_default_timezone_set('America/New_York');

	// CONNECTTION VARIABLE
	$connection = mysqli_connect('localhost', 'root', 'root', 'echo_chamber_db');

	// CONNECTION ERROR
	if(mysqli_connect_errno()) {
		echo 'Failed to connect: ' . mysqli_connect_errno();
	}

?>