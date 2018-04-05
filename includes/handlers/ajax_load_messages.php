<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	require '../../config/config.php';
	include('../classes/User.php');
	include('../classes/Message.php');


	$limit = 7; // NUMBER OF MESSAGES TO LOAD

	// CREATE NEW MESSAGE OBJECT
	$message = new Message($connection, $_REQUEST['userLoggedIn']);
	
	echo $message->getConvosDropDown($_REQUEST, $limit);

?>