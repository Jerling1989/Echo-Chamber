<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('../../config/config.php');
	include('../classes/User.php');
	include('../classes/Post.php');

	$limit = 10; // NUMBER OF POSTS TO BE LOADED PER CALL

	// CREATE NEW POST OBJECT OF USERLOGGEDIN
	$posts = new Post($connection, $_REQUEST['userLoggedIn']);
	// RUN FUNCTION TO LOAD POST FROM FRIENDS
	$posts->loadPostsFriends($_REQUEST, $limit);

?>