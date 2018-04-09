<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

	// IF ID IS SET IN URL
	if (isset($_GET['id'])) {
		// SET $ID TO ID IN URL
		$id = $_GET['id'];
		// ELSE SET $ID TO 0
	} else {
		$id = 0;
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


	<!-- MAIN COLUMN -->
	<div class="main-column column" id="main-column">
		<!-- WHERE THE POST IS LOADED -->
		<div class="posts_area">
			<?php

				$post = new Post($connection, $userLoggedIn);
				$post->getSinglePost($id)

			?>
		</div>
		<!-- END WHERE THE POST IS LOADED -->
	</div>
	<!-- END MAIN COLUMN -->








</body>
</html>