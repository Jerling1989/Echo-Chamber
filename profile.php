<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

	$message_obj = new Message($connection, $userLoggedIn);
	
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

	// IF USER HITS SUBMIT MESSAGE BUTTON (PROFILE MESSAGE TAB FORM)
	if (isset($_POST['post_message'])) {
		if (isset($_POST['message_body'])) {
			$body = mysqli_real_escape_string($connection, $_POST['message_body']);
			$date = date('Y-m-d H:i:s');
			$message_obj->sendMessage($username, $body, $date);
		}

		$link = '#profile-tabs a[href="#messages_div"]';

		echo "<script>
						$(function() {
							$('".$link."').tab('show');
						});
					</script>";
	}

	// CREATE NEW USER OBJECT FOR OWNER OF PROFILE
	$profile_user_obj = new User($connection, $username);
	// CREATE NEW USER OBJECT FOR USER LOGGED IN
	$logged_in_user_obj = new User($connection, $userLoggedIn);

?>

<!-- SCROLL TO TOP -->
<a href="#top-top" data-offset="100">
	<button type="button" id="new-post" class="btn btn-outline-light">
		<i class="fas fa-chevron-up fa-lg"></i>
	</button>
</a>
<!-- END SCROLL TO TOP -->

<!-- TOP USER INFO PANEL -->
<div class="column profile-info col-xl-10 col-lg-12 col-md-12">
<div class="row">
	<!-- LEFT SECTION -->
	<div class="col-lg-4 col-md-4 text-center" id="profile-details">
		<div class="row">
			<div class="col-lg-12 col-md-12 text-center">
				<!-- USER PROFILE PICTURE -->
				<img src="<?php echo $user_array['profile_pic']; ?>" />
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 col-md-12 text-center">
				<p><?php echo $user_array['first_name'].' '.$user_array['last_name']; ?></p>
				<p><?php echo $user_array['username']; ?></p>
				<!-- MUTUAL FRIEND CALCULATION SCRIPT -->
				<p><?php
					if ($userLoggedIn != $username) {
						echo $logged_in_user_obj->getMutualFriends($username) . ' Mutual friends';
					}
				?></p>
				<!-- END MUTUAL FRIEND CALCULATION SCRIPT -->
			</div>
		</div>
	</div>
	<!-- END LEFT SECTION -->

	<!-- RIGHT SECTION -->
	<div class="col-lg-8 col-md-8">
		<!-- USER PROFILE INFO -->
		<div class="row profile-stats">
			<div class="col-md-4 col-4 text-center">
				<p><?php echo 'Friends: '.$num_friends; ?></p>
			</div>

			<div class="col-md-4 col-4 text-center">
				<p><?php echo 'Posts: '.$user_array['num_posts']; ?></p>
			</div>

			<div class="col-md-4 col-4 text-center">
				<p><?php echo 'Likes: '.$user_array['num_likes']; ?></p>
			</div>
		</div>
		<!-- END USER PROFILE INFO -->

		<div class="row profile-buttons">
			<!-- POST SOMETHING MODAL TRIGGER -->
			<div class="col-md-4 col-4 text-center">
				<span data-toggle="modal" data-target="#post-modal">
				<button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Post to Profile">
					<i class="fas fa-edit fa-2x"></i>
				</button>
				</span>
			</div>

			<!-- SEND MESSAGE BUTTON -->
			<div class="col-md-4 col-4 text-center">
				<a href="messages.php?u=<?php echo $username; ?>">
				<button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Send Message">
					<i class="fas fa-envelope fa-2x"></i>
				</button>
				</a>
			</div>

			<!-- FRIEND BUTTON (ADD, DELETE, ACCEPT, SENT) -->
			<div class="col-md-4 col-4 text-center">
				<form action="<?php echo $username; ?>" method="POST">
					<?php 
						// CHECK IF ACCOUNT OF PROFILE IS CLOSED
						if ($profile_user_obj->isClosed()) {
							header('Location: user_closed.php');
						}
						// MAKE SURE USER IS NOT ON THIER OWN PROFILE PAGE
						if ($userLoggedIn != $username) {

							// CHECK IF USER IS FRIENDS WITH PROFILE USER
							if ($logged_in_user_obj->isFriend($username)) {
								echo '<button type="submit" name="remove_friend" class="btn btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Delete Friend">
												<i class="fas fa-user-times fa-2x"></i>
											</button><br />';
								// CHECK IF USER RECIEVED FRIEND REQUEST FROM PROFILE ACCOUNT
							} else if ($logged_in_user_obj->didReceiveRequest($username)) {
								echo '<button type="submit" name="respond_request" class="btn btn-warning"  data-toggle="tooltip" data-placement="bottom" title="Respond to Request">
												<i class="fas fa-check-square fa-2x"></i>
											</button><br />';
								// CHECK IF USER SENT FRIEND REQUEST TO PROFILE ACCOUNT
							} else if ($logged_in_user_obj->didSendRequest($username)) {
								echo '<button name="" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Request Sent">
												<i class="fas fa-clock fa-2x"></i>
											</button><br />';
								// ELSE DISPLAY ADD FRIEND BUTTON
							} else {
								echo '<button type="submit" name="add_friend" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Add Friend">
												<i class="fas fa-user-plus fa-2x"></i>
											</button><br />';
							}
						}
					?>
				</form>

				<?php
					// IF USER IS ON OWN PROFILE, LINK BUTTON TO SETTINGS PAGE
					if ($userLoggedIn == $username) {
							echo '<a href="settings.php"><button class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Profile Settings">
											<i class="fas fa-cogs fa-2x"></i>
										</button></a><br />';
						}
				?>
			</div>
			<!-- END FRIEND BUTTON (ADD, DELETE, ACCEPT, SENT) -->
		</div>
	</div>
	<!-- END RIGHT SECTION -->
</div>
</div>
<!-- END TOP USER INFO PANEL -->

<!-- PROFILE FEED -->
<div class="profile-main-column column col-xl-10 col-lg-12 col-md-12">
	<!-- FEED TITLE -->
	<div class="text-center">
		<h2>The Latest From <?php echo $user_array['first_name'].' '.$user_array['last_name']; ?></h2>
	</div>
	<hr />
	<!-- DIV TO DISPLAY POSTS -->
	<div class="posts_area"></div>
	<!-- LOADING GIF -->
	<div id="loading"><img src="assets/img/icons/loading.gif" /></div>
	<!-- NO POSTS MESSAGE -->
	<div id="no-posts" class="text-center">
		<br />
		<h4>No Posts Yet</h4>
		<p>To populate your newsfeed post some statuses and add some friends!</p>
	</div>
</div>
<!-- END PROFILE FEED -->

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
				<!-- MODAL POST FORM -->
        <form class="profile_post" action="" method="POST">
        	<div class="form-group">
        		<!-- POST BODY -->
        		<textarea class="form-control" name="post_body"></textarea>
        		<!-- POST INFO (USER_TO & USER_FROM) -->
        		<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>" />
        		<input type="hidden" name="user_to" value="<?php echo $username; ?>" />
        	</div>
        </form>
      </div>
			<!-- MODAL FOOTER -->
      <div class="modal-footer">
      	<!-- CANCEL POST -->
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- SEND POST -->
        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
      </div>
      <!-- END MODAL FOOTER -->
    </div>
  </div>
</div>
<!-- END POST MODAL -->


<!-- POST LOADING SCRIPT (PROFILE FEED) -->
<script>
	// CREATE USER LOGGED IN VARIABLE
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	// CREATE PROFILE USERNAME VARIABLE
	var profileUsername = '<?php echo $username; ?>';

	// DOCUMENT READY FUNCTION
	$(document).ready(function() {
		// SHOW LOADING GIF
		$('#loading').show();

		// ORIGINAL AJAX REQUEST FOR LOADING FIRST POSTS
		$.ajax({
			url: 'includes/handlers/ajax_load_profile_posts.php',
			type: 'POST',
			data: 'page=1&userLoggedIn=' + userLoggedIn + '&profileUsername=' + profileUsername,
			cache:false,

			success: function(data) {
				// HIDE LOADING GIF
				$('#loading').hide();
				// LOAD POSTS ONTO POSTS_AREA DIV
				$('.posts_area').html(data);
			}
		});

		// AUTO LOAD POSTS (INFINITE SCROLLING) FUNCTION
		$(window).scroll(function() {
			// POSTS_AREA DIV HEIGHT VARIABLE
			var height = $('.posts_area').height();
			// SCROLLTOP VARIABLE
			var scroll_top = $(this).scrollTop();
			// VARIABLE FOR NEXT PAGE (MORE POSTS)
			var page = $('.posts_area').find('.nextPage').val();
			// VARIABLE FOR NO MORE POSTS
			var noMorePosts = $('.posts_area').find('.noMorePosts').val();

			// CHECK IF THE PAGE IS SCROLLED TO THE BOTTOM OF POSTS_AREA DIV
			// AND THERE ARE ALSO MORE POSTS
			if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {

				// SHOW LOADING GIF
				$('#loading').show();

				// VARIABLE OF AJAX REQUEST FOR MORE POSTS
				var ajaxReq = $.ajax({
					url: 'includes/handlers/ajax_load_profile_posts.php',
					type: 'POST',
					data: 'page=' + page + '&userLoggedIn=' + userLoggedIn + '&profileUsername=' + profileUsername,
					cache:false,

					success: function(response) {
						// REMOVE CURRENT .NEXTPAGE
						$('.posts_area').find('.nextPage').remove();
						// REMOVE CURRENT NOMORE POSTS
						$('.posts_area').find('.noMorePosts').remove();

						// HIDE LOADING GIF
						$('#loading').hide();
						// LOAD POSTS ONTO POSTS_AREA DIV
						$('.posts_area').append(response);
					}
				});
			} // END IF
			return false;
		}); // END AUTO LOAD POSTS FUNCTION
	});
</script>
<!-- END POST LOADING SCRIPT (PROFILE FEED) -->

</div>
<!-- END WRAPPER DIV -->
</body>
</html>