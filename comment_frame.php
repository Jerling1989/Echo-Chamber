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

	?>

	<script>
		// CREATE TOGGLE FUNCTION
		function toggle() {
			// GET COMMENT SECTION BY ID, STORE IN VARIABLE
			var element = document.getElementById('comment_section');

			// IF SECTION IS VISIBLE, THEN HIDE IT
			if (element.style.display == 'block') {
				element.style.display = 'none';
				// IF SECTION IS HIDDEN, MAKE IT VISIBLE
			} else {
				element.style.display = 'block';
			}
		}
	</script>

	<?php

		// GET ID OF POST
		if (isset($_GET['post_id'])) {
			$post_id = $_GET['post_id'];
		}
		// DATABASE QUERY TO GET ADDED_BY AND USER_TO FROM POST TABLE
		$user_query = mysqli_query($connection, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
		// STORE QUERY RESULTS INTO $ROW ARRAY
		$row = mysqli_fetch_array($user_query);

		$posted_to = $row['added_by'];

		if (isset($_POST['postComment' . $post_id])) {
			$post_body = $_POST['post_body'];
			$post_body = mysqli_escape_string($connection, $post_body);
			$date_time_now = date('Y-m-d H:i:s');
			$insert_post = mysqli_query($connection, "INSERT INTO comments VALUES ('', '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id')");

			echo '<p>Comment Posted!</p>';
		}

	?>

	<!-- COMMENT FORM -->
	<form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
		<!-- COMMENT TEXTAREA -->
		<textarea name="post_body"></textarea>
		<!-- COMMENT SUBMIT -->
		<input type="submit" name="postComment<?php echo $post_id; ?>" value="Post" />
	</form>
	<!-- END COMMENT FORM -->


	<!-- LOAD COMMENTS -->
	<?php

		// DATABASE QUERY TO GET COMMENT INFO
		$get_comments = mysqli_query($connection, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
		// COUNT NUMBER OF QUERY RESULTS
		$count = mysqli_num_rows($get_comments);

		// CHECK IF THERE ARE ANY RESULTS
		if ($count != 0) {
			// WHILE THERE ARE QUERY RESULTS...
			while($comment = mysqli_fetch_array($get_comments)) {
				// CREATE THESE COMMENT VARIABLES
				$comment_body = $comment['post_body'];
				$posted_to = $comment['posted_to'];
				$posted_by = $comment['posted_by'];
				$date_added = $comment['date_added'];
				$removed = $comment['removed'];

			}

		}


	?>


















</body>
</html>