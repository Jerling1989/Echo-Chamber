<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

	// IF NEW POST IS SUBMITTED
	if (isset($_POST['post'])) {

		// CREATE POST IMAGE VARIABLES
		$uploadOk = 1;
		$imageName = $_FILES['fileToUpload']['name'];
		$errorMessage = '';

		// IF $IMAGENAME IS NOT BLANK
		if ($imageName != '') {
			// CREATE FILE PATH TO IMAGE
			$targetDir = 'assets/img/posts/';
			$imageName = $targetDir . uniqid() . basename($imageName);
			$imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

			// IF IMAGE IS TOO LARGE
			if ($_FILES['fileToUpload']['size'] > 10000000) {
				$errorMessage = 'Sorry your file is too large';
				$uploadOk = 0;
			}

			// IF IMAGE IS NOT PROPER FILE EXTENSION
			if (strtolower($imageFileType) != 'jpeg' && strtolower($imageFileType) != 'png' && strtolower($imageFileType) != 'jpg') {

				$errorMessage = 'Sorry, only jpeg, jpg and png files are allowed';
				$uploadOk = 0;
			}

			// IF EVERYTHING WORKS ACCORDINGLY
			if ($uploadOk) {
				if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)) {
					// IMAGE UPLOADED OK
				} else {
					// IMAGE DID NOT UPLOAD
					$uploadOk = 0;
				}
			}

		}

		// IF EVERYTHING WORKS ACCORDINGLY
		if ($uploadOk) {
			// CREATE NEW POST OBJECT
			$post = new Post($connection, $userLoggedIn);
			// RUN FUNCTION TO ADD POST TO DATABASE
			$post->submitPost($_POST['post-text'], 'none', $imageName);

			// ELSE DISPLAY ERROR MESSAGE
		} else {
			echo '<div style="text-align: center;" class="alert alert-danger">
							'.$errorMessage.'
						</div>';
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


	<!-- MAIN COLUMN (NEWSFEED) -->
	<div class="main-column column">
		
		<!-- POST FORM -->
		<form class="post-form" action="index.php" method="POST" enctype="multipart/form-data">
			<!-- POST IMAGE INPUT -->
			<input type="file" name="fileToUpload" id="fileToUpload" />
			<!-- POST TEXTAREA -->
			<textarea name="post-text" id="post-text" placeholder="Got something to say?"></textarea>
			<!-- POST SUBMIT BUTTON -->
			<input type="submit" name="post" id="post-button" value="Post" />
			<hr />
		</form>
		<!-- END POST FORM -->

		<!-- DIV TO DISPLAY POSTS -->
		<div class="posts_area"></div>

		<!-- LOADING GIF -->
		<div id="loading"><img src="assets/img/icons/loading.gif" /></div>
		
	</div>
	<!-- END MAIN COLUMN (NEWSFEED) -->


	<!-- TRENDING PANEL -->
	<div class="user-details column">
		<h4>Popular</h4><br />

		<div class="trends">
			<?php

				$query = mysqli_query($connection, "SELECT * FROM trends ORDER BY hits DESC LIMIT 9");

				foreach ($query as $row) {
					$word = $row['title'];
					$word_dot = strlen($word) >= 14 ? '...' : '';

					$trimmed_word = str_split($word, 14);
					$trimmed_word = $trimmed_word[0];

					echo '<div style="padding: 1px;">';
					echo $trimmed_word . $word_dot;
					echo '<br /></div><br />';
				}

			?>
		</div>
	</div>
	<!-- END TRENDING PANEL -->


	<!-- POST LOADING SCRIPT (NEWSFEED) -->
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
				cache: false,

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


				} // END IF

				return false;

			}); // END AUTO LOAD POSTS FUNCTION

		});

	</script>
	<!-- END POST LOADING SCRIPT (NEWSFEED) -->


	</div>
	<!-- END WRAPPER DIV -->
</body>
</html>