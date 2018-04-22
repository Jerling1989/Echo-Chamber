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

<!-- START GRID ROW FOR PAGE LAYOUT -->
<div class="row">
	<!-- LEFT COLUMN (USER INFO) -->
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
	</div>
	<!-- END LEFT COLUMN (USER INFO) -->

	<!-- MAIN COLUMN TO DISPLAY POST -->
	<div class="col-lg-8 col-md-12 column" id="single-post">
		<!-- WHERE THE POST IS LOADED -->
		<div class="posts_area">
			<?php
				$post = new Post($connection, $userLoggedIn);
				$post->getSinglePost($id)
			?>
		</div>
		<!-- END WHERE THE POST IS LOADED -->
	</div>
	<!-- END MAIN COLUMN TO DISPLAY POST -->
</div>
<!-- END START GRID ROW FOR PAGE LAYOUT -->

</div>
<!-- END WRAPPER DIV -->
</body>
</html>