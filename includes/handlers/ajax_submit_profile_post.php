<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	require '../../config/config.php';
	include('../classes/User.php');
	include('../classes/Post.php');
	include('../classes/Notification.php');

	// IF PROFILE POST FORM IS SUBMITTED WITH CONTENT IN POST BODY
	if(isset($_POST['post_body'])) {
		// CREATE NEW POST OBJECT FOR USER WHO POSTED IT
		$post = new Post($connection, $_POST['user_from']);
		// SUBMIT POST TO USER PROFILE
		$post->submitPost($_POST['post_body'], $_POST['user_to']);
	}

?>