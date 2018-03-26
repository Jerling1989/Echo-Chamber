<?php

	include('includes/header.php');
	include('includes/classes/User.php');
	include('includes/classes/Post.php');

	if (isset($_POST['post'])) {
		$post = new Post($connection, $userLoggedIn);
		$post->submitPost($_POST['post-text'], 'none');
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



	<div class="main-column column">
		
		<form class="post-form" action="index.php" method="POST">
			<textarea name="post-text" id="post-text" placeholder="Got something to say?"></textarea>
			<input type="submit" name="post" id="post-button" value="Post" />
			<hr />
		</form>

		<?php
			$post = new Post($connection, $userLoggedIn);
			$post->loadPostsFriends();
		?>

		<img id="loading" src="assets/img/icons/loading.gif" />

	</div>

	<script type="text/javascript">
		
		var userLoggedIn = '<?php ehco $userLoggedIn; ?>';

		$(document).ready(function() {
			// LOADING GIF
			$('#loading').show();

			// ORIGINAL AJAX REQUEST FOR LOADING FIRST POSTS
			$.ajax({
				url: 'includes/handlers/ajax_load_posts.php',
				type: 'POST',
				data: 'page=1&userLoggedIn=' + userLoggedIn,
				cache: false,
				
			});

		});

	</script>


	</div>
	<!-- END WRAPPER DIV -->
</body>
</html>