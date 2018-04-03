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


<!-- USER DETAILS PANEL -->
<div class="user-details column">
	<!-- PROFILE PIC AND LINK -->
	<a href="<?php echo $userLoggedIn; ?>">
		<img src="<?php echo $user['profile_pic']; ?>" />
	</a>

	<!-- NAME, POSTS, & LIKES -->
	<div class="user-details-left-right">
		<a href="<?php echo $userLoggedIn; ?>">
			<?php
				echo $user['first_name'] . ' ' . $user['last_name'] . '<br />';
				echo $user['username'];
			?>
		</a>
		<br /><br />
		<?php
			echo 'Posts: ' . $user['num_posts'] . '<br />';
			echo 'Likes: ' . $user['num_likes'];
		?>
	</div>
	<!-- END NAME, POSTS, & LIKES -->
</div>
<!-- END USER DETAILS PANEL -->


<!-- MAIN MESSAGES COLUMN -->
<div class="main-column column" id="main-column">
	<?php
		// IF USER_TO IS NOT NEW CREATE HEADING WITH LINK TO USER ACCOUNT AND FIRST & LAST NAME
		if ($user_to != 'new') {
			echo '<h4>You and <a href="'.$user_to.'">' . $user_to_obj->getFirstAndLastName() . '</a></h4><hr /><br />';
			echo '<div class="loaded_messages" id="scroll_messages">';
			echo $message_obj->getMessages($user_to);
			echo '</div>';
		} else {
			echo '<h4>New Message</h4>';
		}
	?>

	<!-- MESSAGES DIV -->
	<div class="message_post">
		<!-- MESSAGE FORM -->
		<form action="" method="POST">
			<?php
				// FORM FOR SENDING MESSAGE TO NEW USER
				if ($user_to == 'new') {
					echo 'Select the friend you would like to message <br /><br />';
					echo 'To: <input type="text" />';
					echo '<div class="results"></div>';

					// FORM FOR CONTINUING TO SEND MESSAGES TO SELECTED USER
				} else {
					echo '<textarea name="message_body" id="message_textarea" placeholder="Write your message..."></textarea>';
					echo '<input type="submit" name="post_message" class="info" id="message_submit" value="Send" />';
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
<!-- END MAIN MESSAGES COLUMN -->


<!-- CONVERSATION PANEL -->
<div class="user-details column" id="conversations">
	<h4>Conversations</h4>

	<div class="loaded_conversations">
		<?php echo $message_obj->getConvos(); ?>
	</div>

	<br />
	<a href="messages.php?u=new">New Message</a>
</div>
<!-- END CONVERSATION PANEL -->




















</body>
</html>