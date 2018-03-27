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


				// CURRENT TIME
				$date_time_now = date('Y-m-d H:i:s');
				// DATE COMMENT WAS ADDED VARIABLE
				$start_date = new DateTime($date_added);
				// CURRENT DATE VARIABLE
				$end_date = new DateTime($date_time_now);
				// DIFFERENCE BETWEEN BOTH DATE VARIABLES
				$interval = $start_date->diff($end_date);
				// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE YEAR
				if ($interval->y >= 1) {
					if($interval == 1) {
						$time_message = $interval->y . ' year ago'; // ONE YEAR
					} else {
						$time_message = $interval->y . ' years ago'; // MULTIPLE YEARS
					}
					// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE MONTH
				} else if ($interval->m >= 1) {
					if ($interval->d == 0) {
						$days = ' ago'; // NO ADDITIONAL DAYS
					} else if ($interval->d == 1) {
						$days = $interval->d . ' day ago'; // ONE ADDITIONAL DAYS
					} else {
						$days = $interval->d . ' days ago'; // MULTIPLE ADDITIONAL DAYS
					}

					if($interval->m == 1) {
						$time_message = $interval->m . ' month ago' . $days; // ONE MONTH PLUS DAYS
					} else {
						$time_message = $interval->m . ' months ago' . $days; // MULTIPLE MONTHS PLUS DAYS
					}
					// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE DAY
				} else if ($interval->d >= 1) {
					if ($interval->d == 1) {
						$time_message = 'Yesterday';
					} else {
						$time_message = $interval->d . ' days ago';
					}
					// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE HOUR
				} else if ($interval->h >= 1) {
					if ($interval->h == 1) {
						$time_message = $interval->h . ' hour ago'; // ONE HOUR
					} else {
						$time_message = $interval->h . ' hours ago'; // MULTIPLE HOURS
					}
					// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE MINUTE
				} else if ($interval->i >= 1) {
					if ($interval->i == 1) {
						$time_message = $interval->i . ' minute ago'; // ONE MINUTE
					} else {
						$time_message = $interval->i . ' minutes ago'; // MULTIPLE MINUTES
					}
					// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE SECOND
				} else {
					if ($interval->s < 30) {
						$time_message = 'Just now'; // LESS THAN 30 SECONDS
					} else {
						$time_message = $interval->s . ' seconds ago'; // OVER 30 SECONDS
					}
				}


				// CREATE NEW USER OBJECT FOR USER THAT POSTED COMMENT
				$user_obj = new User($connection, $posted_by);

			}

		}

	?>


	<div class="comment_section">
		<a href="<?php echo $posted_by; ?>" target="_parent">sSFsdaf</a>
	</div>


















</body>
</html>