<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');
	
	// CHECK IF USERNAME IS SET FOR URL
	if(isset($_GET['profile_username'])) {
		// USERNAME VARIABLE
		$username = $_GET['profile_username'];
		// DATABASE QUERY
		$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$username'");
		// STORE QUERY RESULTS IN ARRAY
		$user_array = mysqli_fetch_array($user_details_query);
		// GET NUMBER OF USER FRIENDS
		$num_friends = (substr_count($user_array['friend_array'], ',')) - 1;
	}

	// IF USER HITS REMOVE FRIEND BUTTON
	if (isset($_POST['remove_friend'])) {
		// CREATE NEW USER OBJECT FOR LOGGED IN USER
		$user = new User($connection, $userLoggedIn);
		// RUN FUNCTION TO REMOVE USER OF PROFILE
		$user->removeFriend($username);
	}

	// IF USER HITS ADD FRIEND BUTTON
	if (isset($_POST['add_friend'])) {
		// CREATE NEW USER OBJECT FOR LOGGED IN USER
		$user = new User($connection, $userLoggedIn);
		// RUN FUNCTION TO SEND REQUEST TO USER OF PROFILE
		$user->sendRequest($username);
	}

	// IF USER HITS TO RESPOND TO FRIEND REQUEST
	if (isset($_POST['respond_request'])) {
		// REDIRECT TO FRIEND REQUEST PAGE
		header('Location: requests.php');
	}

?>

	<style type="text/css">
		.wrapper {
			margin-left: 0px;
			padding-left: 0px;
		}
	</style>

	<!-- LEFT USER INFO PANEL -->
	<div class="profile-left">
		<!-- USER PROFILE PICTURE -->
		<img src="<?php echo $user_array['profile_pic']; ?>" />

		<!-- USER PROFILE INFO -->
		<div class="profile-info">
			<p><?php echo 'Posts: ' . $user_array['num_posts']; ?></p>
			<p><?php echo 'Likes: ' . $user_array['num_likes']; ?></p>
			<p><?php echo 'Friends: ' . $num_friends; ?></p>
		</div>
		<!-- END USER PROFILE INFO -->

		<!-- FRIEND REQUEST BUTTON FORM -->
		<form action="<?php echo $username; ?>" method="POST">
			<?php 
				// CREATE NEW USER OBJECT FOR OWNER OF PROFILE
				$profile_user_obj = new User($connection, $username);

				// CHECK IF ACCOUNT OF PROFILE IS CLOSED
				if ($profile_user_obj->isClosed()) {
					header('Location: user_closed.php');
				}

				// CREATE NEW USER OBJECT FOR USER LOGGED IN
				$logged_in_user_obj = new User($connection, $userLoggedIn);

				// MAKE SURE USER IS NOT ON THIER OWN PROFILE PAGE
				if ($userLoggedIn != $username) {

					// CHECK IF USER IS FRIENDS WITH PROFILE USER
					if ($logged_in_user_obj->isFriend($username)) {
						echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend" /><br />';
						// CHECK IF USER RECIEVED FRIEND REQUEST FROM PROFILE ACCOUNT
					} else if ($logged_in_user_obj->didReceiveRequest($username)) {
						echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request" /><br />';
						// CHECK IF USER SENT FRIEND REQUEST TO PROFILE ACCOUNT
					} else if ($logged_in_user_obj->didSendRequest($username)) {
						echo '<input type="submit" name="" class="default" value="Request Sent" /><br />';
						// ELSE DISPLAY ADD FRIEND BUTTON
					} else {
						echo '<input type="submit" name="add_friend" class="success" value="Add Friend" /><br />';
					}
				}
			?>

		</form>
		<!-- END FRIEND REQUEST BUTTON FORM -->

		<!-- POST SOMETHING MODAL TRIGGER -->
		<input type="submit" class="deep-blue" data-toggle="modal" data-target="#post-modal" value="Post Something" />

	</div>
	<!-- END LEFT USER INFO PANEL -->





	<div class="main-column column">

		<?php echo $username; ?>

	</div>



	<!-- POST MODAL (.MODAL-LG) -->
	<div class="modal fade" id="post-modal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">

				<!-- MODAL HEADER -->
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Post Something!</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

				<!-- MODAL BODY -->
	      <div class="modal-body">
	        <p>This will appear on the user's profile page and also their newsfeed for your friends to see!</p>

	        <form class="profile_post" action="" method="POST">
	        	<div class="form-group">
	        		<textarea class="form-control" name="post_body"></textarea>
	        		<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>" />
	        		<input type="hidden" name="user_to" value="<?php echo $username; ?>" />
	        	</div>
	        </form>
	      </div>

				<!-- MODAL FOOTER -->
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Post</button>
	      </div>

	    </div>
	  </div>
	</div>
	<!-- END POST MODAL -->




	</div>
	<!-- END WRAPPER DIV -->
</body>
</html>