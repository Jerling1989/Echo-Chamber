<?php

	include('../../config/config.php');
	include('../classes/User.php');
	include('../classes/Post.php');

	$limit = 10; // NUMBER OF POSTS TO BE LOADED PER CALL

	$posts = new Post($connection, $_REQUEST['userLoggedIn']);
	$posts->loadPostsFriends($_REQUEST, $limit);

?>