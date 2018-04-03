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


<div class="main-column column" id="main-column">
	<?php
		// IF USER IS NOT NEW CREATE HEADING WITH LINK TO USER ACCOUNT AND FIRST & LAST NAME
		if ($user_to != 'new') {
			echo '<h4>You and <a href="$user_to">' . $user_to_obj->getFirstAndLastName() . '</a></h4><hr /><br />';
		}
	?>

	<div class="loaded_messages">
		<form action="" method="POST">
			<?php

				if ($user_to == 'new') {
					echo 'Select the friend you would like to message <br /><br />';
					echo 'To: <input type="text" />';
					echo '<div class="results"></div>';
				} else {
					echo '<textarea name="message_body" id="message_textarea" placeholder="Write your message..."></textarea>';
					echo '<input type="submit" name="post_message" class="info" id="message_submit" value="Send" />';
				}

			?>
		</form>
	</div>

</div>




















</body>
</html>