<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

	// CREATE NEW MESSAGE OBJECT
	$message_obj = new Message($connection, $userLoggedIn);

	// IF USERNAME IS SET IN URL
	if (isset($_GET['u'])) {
		// SET $USER_TO TO USERNAME IN URL
		$user_to = $_GET['u'];
		// ELSE SET $USER_TO TO MOST RECENT USER MESSAGED
	} else {
		$user_to = $message_obj->getMostRecentUser();
		// IF THERE IS NO MOST RECENT USER, START NEW MESSAGE
		if ($user_to == false) {
			$user_to = 'new';
		}
	}

	// IF USER IS NOT NEW CREATE USER OBJECT OUT OF $USER_TO VARIABLE
	if ($user_to != 'new') {
		$user_to_obj = new User($connection, $user_to);
	}

	// IF USER SUBMITS A MESSAGE
	if (isset($_POST['post_message'])) {
		if (isset($_POST['message_body'])) {
			// SET MESSAGE VARIABLES
			$body = mysqli_real_escape_string($connection, $_POST['message_body']);
			$date = date('Y-m-d H:i:s');
			// CREATE NEW MESSAGE OBJECT
			$message_obj->sendMessage($user_to, $body, $date);
		}
	}

?>

<!-- START GRID ROW FOR PAGE LAYOUT -->
<div class="row">

	<!-- LEFT COLUMN (USER INFO AND CONVERSATION DOCK) -->
	<div class="col-lg-4 col-md-0">
		<!-- USER DETAILS PANEL -->
		<div class="column" id="user-details">
			<div class="row">	
				<div class="col-lg-6 text-center">
					<!-- PROFILE PIC AND LINK -->
					<a href="<?php echo $userLoggedIn; ?>">
						<img class="img-fluid" src="<?php echo $user['profile_pic']; ?>" />
					</a>
				</div>
				<div class="col-lg-6">
					<!-- NAME, POSTS, & LIKES -->
					<div style="padding-top: 10px;">
						<a href="<?php echo $userLoggedIn; ?>">
							<?php
								echo '<span class="user-links">'.$user['first_name'].' '.$user['last_name'].'</span><br />';
								echo '<span class="user-links">'.$user['username'].'</span>';
							?>
						</a>
						<br /><br />
						<?php
							echo '<span class="user-stats">Friends: '.$user_friends.'</span><br />';
							echo '<span class="user-stats">Posts: '.$user['num_posts'].'</span><br />';
							echo '<span class="user-stats">Likes: '.$user['num_likes'].'</span><br />';
						?>
					</div>
					<!-- END NAME, POSTS, & LIKES -->
				</div>
			</div>
		</div>
		<!-- END USER DETAILS PANEL -->

		<!-- CONVERSATIONS PANEL -->
		<div class="column" id="trending">
			<h4>Your Conversations</h4>
			<!-- LOAD CONVERSATIONS -->
			<div class="loaded_conversations">
				<?php echo $message_obj->getConvos(); ?>
			</div>
			<br />
			<!-- LINK TO NEW MESSAGE -->
			<a href="messages.php?u=new">New Message</a>
		</div>
		<!-- END CONVERSATIONS PANEL -->
	</div>
	<!-- END LEFT COLUMN (USER INFO AND CONVERSATION DOCK) -->

	<!-- MAIN COLUMN (MESSAGE CONVERSATION PANEL) -->
	<div class="col-md-12 col-lg-8">
	<div class="column" id="messages-panel">
		<?php
			// IF USER_TO IS NOT NEW CREATE HEADING WITH LINK TO USER ACCOUNT AND FIRST & LAST NAME
			if ($user_to != 'new') {
				echo '<a href="'.$user_to.'"><img id="message-pic" src="'.$user_to_obj->getProfilePic().'" /></a><h4>You and <a href="'.$user_to.'">'.$user_to_obj->getFirstAndLastName().'</a></h4><hr />';
				// LOAD CONVERSATION WITH SELECTED USER
				echo '<div class="loaded_messages" id="scroll_messages">';
				echo $message_obj->getMessages($user_to);
				echo '</div>';
			} else {
				echo '<h4>New Message</h4><hr />';
			}
		?>

		<!-- MESSAGES DIV -->
		<div class="message_post">
			<!-- MESSAGE FORM -->
			<form action="" method="POST">
				<?php
					// FORM FOR SENDING MESSAGE TO NEW USER
					if ($user_to == 'new') {
						echo 'Search for the friend you would like to message<br /><br />';
						?>
						<input type="text" class="form-control" onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' name="q" placeholder="Name, Username, etc..." autocomplete="off" id="search_messenger" />
						<?php
						// DIV TO LOAD SEARCH RESULTS
						echo '<div class="results"></div>';

						// FORM FOR CONTINUING TO SEND MESSAGES TO SELECTED USER
					} else {
						echo '<hr /><div class="row">';
						echo '<div class="col-md-9 col-sm-9 col-9"><textarea name="message_body" id="message_textarea" placeholder="Write your message..."></textarea></div>';
						echo '<div class="col-md-3 col-sm-3 col-3"><input type="submit" class="btn btn-success" name="post_message" id="message_submit" value="Send" /></div>';
						echo '</div>';
					}
				?>
			</form>
			<!-- END MESSAGE FORM -->
		</div>
		<!-- END MESSAGES DIV -->

		<!-- SCRIPT TO LOAD MESSAGES PROPERLY -->
		<script>
			var div = document.getElementById('scroll_messages');
			div.scrollTop = div.scrollHeight;
		</script>
		<!-- END SCRIPT TO LOAD MESSAGES PROPERLY -->
	</div>
	</div>
	<!-- END MAIN COLUMN (MESSAGE CONVERSATION PANEL) -->
</div>
<!-- END GRID ROW FOR PAGE LAYOUT -->

</div>
<!-- END WRAPPER DIV -->
</body>
</html>