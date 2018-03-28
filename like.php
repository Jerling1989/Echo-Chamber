<!DOCTYPE html>
<html>
<head>
	<!-- PAGE TITLE -->
	<title></title>
	<!-- RESET CSS LINK -->
  <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
  <!-- BOOTSTRAP CSS LINK -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <!-- CUSTOM CSS LINK -->
  <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
</head>
<body>

	<?php

		// REQUIRE/INCLUDE NECCESSARY FILES AND SCRIPTS
		require 'config/config.php';
		include('includes/classes/User.php');
		include('includes/classes/Post.php');

		// CHECK IF USER IS SIGNED IN
		if(isset($_SESSION['username'])) {
			// CREATE VARIABLE FOR USERNAME
			$userLoggedIn = $_SESSION['username'];

			// QUERY TO FIND USER DETAILS
			$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$userLoggedIn'");
			// STORE USER DETAILS INTO ARRAY
			$user = mysqli_fetch_array($user_details_query);

			// IF NOT SIGNED IN REDIRECT USER TO LOGIN PAGE
		} else {
			header('Location: register.php');
		}

		// GET ID OF POST
		if (isset($_GET['post_id'])) {
			$post_id = $_GET['post_id'];
		}

		// DATABASE QUERY TO GET LIKE COUNT INFO
		$get_likes = mysqli_query($connection, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
		// STORE QUERY DATA IN $ROW ARRAY
		$row = mysqli_fetch_array($get_likes);
		// GET NUMBER OF LIKES
		$total_likes = $row['likes'];
		// GET AUTHOR OF POST
		$user_likes = $row['added_by'];

		// DATABASE QUERY TO GET INFO ABOUT POST AUTHOR
		$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$user_liked'");
		// STORE QUERY DATA IN $ROW ARRAY
		$row = mysqli_fetch_array($user_details_query);


		// LIKE BUTTON
		// UNLIKE BUTTON


		// DATABASE QUERY TO CHECK IF USER LIKED POST
		$check_query = mysqli_query($connection, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
		// STORE NUMBER OF RESULTS IN $NUM_ROWS VARIABLE
		$num_rows = mysqli_num_rows($check_query);

		// IF THERE ARE RESULTS FROM QUERY
		if (num_rows > 0) {
			echo '';
			// IF THERE ARE NO RESULTS FROM QUERY
		} else {
			echo '';
		}

	?>

</body>
</html>