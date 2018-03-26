<?php

	// ASSIGN SCRIPT TO CURRENT SESSION
	session_start();
	// END SESSION WHEN SCRIPT IS RUN
	session_destroy();
	// REDIRECT TO LOGIN PAGE
	header('Location: ../../register.php');
	
?>