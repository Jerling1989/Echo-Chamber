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

		<!-- MUTUAL FRIEND CALCULATION SCRIPT -->
		<?php 
			if ($userLoggedIn != $username) {
				echo '<div class="profile-info-bottom">';
				echo $logged_in_user_obj->getMutualFriends($username) . ' Mutual friends';
				echo '</div>';
			}
		?>
		<!-- END MUTUAL FRIEND CALCULATION SCRIPT -->

	</div>
	<!-- END LEFT USER INFO PANEL -->




	<!-- PROFILE FEED -->
	<div class="profile-main-column column">

		<!-- TAB NAVIGATION -->
		<ul class="nav nav-tabs" id="profile-tabs" role="tablist">
		  <li class="nav-item">
		    <a class="nav-link active" href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a>
		  </li>
		  <!-- <li class="nav-item">
		    <a class="nav-link" href="#about_div" aria-controls="about_div" role="tab" data-toggle="tab">About</a>
		  </li> -->
		  <li class="nav-item">
		    <a class="nav-link" href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Messages</a>
		  </li>
		</ul>
		<!-- END TAB NAVIGATION -->


		<!-- TAB CONTENT LAYOUT -->
		<div class="tab-content">

			<!-- NEWSFEED TAB -->
			<div class="tab-pane fade show active" role="tabpanel" id="newsfeed_div">
				<!-- DIV TO DISPLAY POSTS -->
				<div class="posts_area"></div>
				<!-- LOADING GIF -->
				<div id="loading"><img src="assets/img/icons/loading.gif" /></div>
			</div>
			<!-- END NEWSFEED TAB -->


			<!-- ABOUT TAB -->
			<!-- <div class="tab-pane fade" role="tabpanel" id="about_div">
			</div> -->
			<!-- END ABOUT TAB -->
			

			<!-- MESSAGES TAB -->
			<div class="tab-pane fade" role="tabpanel" id="messages_div">
				<?php

					echo '<h4>You and <a href="'.$username.'">' . $profile_user_obj->getFirstAndLastName() . '</a></h4><hr /><br />';
					echo '<div class="loaded_messages" id="scroll_messages">';
					echo $message_obj->getMessages($username);
					echo '</div>';
				?>

				<!-- MESSAGES DIV -->
				<div class="message_post">
					<!-- MESSAGE FORM -->
					<form action="" method="POST">
							<textarea name="message_body" id="message_textarea" placeholder="Write your message..."></textarea>'
							<input type="submit" name="post_message" class="info" id="message_submit" value="Send" />
					</form>
					<!-- END MESSAGE FORM -->
				</div>
				<!-- END MESSAGES DIV -->

				<!-- SCRIPT TO LOAD MESSAGES PROPERLY -->
				<script>
		      $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
	          var div = document.getElementById("scroll_messages");
	 
	          if(div != null) {
            	div.scrollTop = div.scrollHeight;  
	          }
		      });
				</script>
				<!-- END SCRIPT TO LOAD MESSAGES PROPERLY -->
			</div>
			<!-- END MESSAGES TAB -->

		</div>
		<!-- END TAB CONTENT LAYOUT -->


		
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
	        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
	      </div>

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