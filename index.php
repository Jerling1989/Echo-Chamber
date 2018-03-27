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


		<div class="posts_area"></div>

		<img id="loading" src="assets/img/icons/loading.gif" />

	</div>


	<script>

		// CREATE USERLOGGEDIN VARIABLE
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';

		// DOCUMENT READY FUNCTION
		$(document).ready(function() {
			// SHOW LOADING GIF
			$('#loading').show();

			// ORIGINAL AJAX REQUEST FOR LOADING FIRST POSTS
			$.ajax({
				url: 'includes/handlers/ajax_load_posts.php',
				type: 'POST',
				data: 'page=1&userLoggedIn=' + userLoggedIn,
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
				var page = $('.posts_area').find('.next').val();
				// VARIABLE FOR NO MORE POSTS
				var noMorePosts = $('posts_area').find('.noMorePosts').val();

				// CHECK IF THE PAGE IS SCROLLED TO THE BOTTOM OF POSTS_AREA DIV
				// AND THERE ARE ALSO MORE POSTS
				if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {

					// SHOW LOADING GIF
					$('#loading').show();

					// VARIABLE OF AJAX REQUEST FOR MORE POSTS
					var ajaxReq = $.ajax({
						url: 'includes/handlers/ajax_load_posts.php',
						type: 'POST',
						data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
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


				}

			});

		});

	</script>


	</div>
	<!-- END WRAPPER DIV -->
</body>
</html>