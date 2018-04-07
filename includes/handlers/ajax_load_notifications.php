<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	require '../../config/config.php';
	include('../classes/User.php');
	include('../classes/Notification.php');


	$limit = 7; // NUMBER OF MESSAGES TO LOAD

	// CREATE NEW MESSAGE OBJECT
	$notification = new Notification($connection, $_REQUEST['userLoggedIn']);

	echo $notification->getNotifications($_REQUEST, $limit);

?>