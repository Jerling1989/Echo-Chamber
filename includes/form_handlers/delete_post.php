<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	require '../../config/config.php';

	// CHECK IF POST ID IS SET
	if (isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
	}

	// CHECK IF RESULT IS SET (DELETE POST CONFIRMED)
	if (isset($_POST['result'])) {
		if ($_POST['result'] == 'true') {
			// DATABASE QUERY TO UPDATE POST TO DELETED IN POSTS TABLE
			$query = mysqli_query($connection, "UPDATE posts SET deleted='yes' WHERE id='$post_id'");
		}
	}

?>