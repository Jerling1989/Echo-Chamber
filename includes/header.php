<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	require 'config/config.php';
	include('includes/classes/User.php');
	include('includes/classes/Post.php');
	include('includes/classes/Message.php');
	include('includes/classes/Notification.php');

	// CHECK IF USER IS SIGNED IN
	if(isset($_SESSION['username'])) {
		// CREATE VARIABLE FOR USERNAME
		$userLoggedIn = $_SESSION['username'];

		// QUERY TO FIND USER DETAILS
		$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$userLoggedIn'");
		// STORE USER DETAILS INTO ARRAY
		$user = mysqli_fetch_array($user_details_query);

		// IF NOT SIGNED IN REDIRECT USER TO LOGIN PAGE
	} else {
		header('Location: register.php');
	}
?>



<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<!-- PAGE TITLE -->
	<title>Echo Chamber</title>
	<!-- FAVICON -->
	<link rel="icon" href="assets/img/favicon.ico" type="image/x-icon" />
	<!-- MOBILE VIEWPORT -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- FONT AWESOME LINKS -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css" integrity="sha384-v2Tw72dyUXeU3y4aM2Y0tBJQkGfplr39mxZqlTBDUZAb9BGoC40+rdFCG0m10lXk" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" integrity="sha384-q3jl8XQu1OpdLgGFvNRnPdj5VIlCvgsDQTQB6owSOHWlAurxul7f+JpUOVdAiJ5P" crossorigin="anonymous">
  <!-- RESET CSS LINK -->
  <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
	<!-- BOOTSTRAP CSS LINK -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <!-- JCROP CSS -->
  <link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css" />
  <!-- CUSTOM CSS LINK -->
  <link rel="stylesheet" type="text/css" href="assets/css/style.css" />

	<!-- JQUERY CDN LINK -->
	<script
	  src="https://code.jquery.com/jquery-3.3.1.min.js"
	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  crossorigin="anonymous"></script>
	<!-- POPPER.JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!-- BOOTSTRAP JQUERY -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- BOOTBOX.JS -->
	<script src="assets/js/bootbox.min.js"></script>
	<!-- JCROP.JS -->
	<script src="assets/js/jquery.Jcrop.js"></script>
	<!-- JCROP.JS -->
	<script src="assets/js/jcrop_bits.js"></script>
	<!-- ECHOCHAMBER.JS -->
	<script src="assets/js/echochamber.js"></script>

</head>
<body>

	<!-- TOP BAR -->
	<div id="top-bar">

		<!-- LOGO -->
		<div id="logo">
			<a href="index.php">Swirlfeed!</a>
		</div>
		<!-- END LOGO -->

		<!-- NAVIGATION -->
		<nav>

			<!-- PHP SCRIPT FOR NOTIFICATION BADGES -->
			<?php

				// UNREAD MESSAGES
				$messages = new Message($connection, $userLoggedIn);
				$num_messages = $messages->getUnreadNumber();

				// UNREAD NOTIFICATIONS
				$notifications = new Notification($connection, $userLoggedIn);
				$num_notifications = $notifications->getUnreadNumber();

			?>
			<!-- END PHP SCRIPT FOR NOTIFICATION BADGES -->


			<!-- USER PROFILE -->
			<a href="<?php echo $userLoggedIn; ?>">
				<?php
					echo $user['first_name'];
				?>
			</a>
			<!-- HOME -->
			<a href="index.php">
				<i class="fas fa-home fa-lg"></i>
			</a>
			<!-- MESSAGES -->
			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
				<i class="fas fa-envelope fa-lg"></i>
				<?php
					if ($num_messages > 0) {
						echo '<span class="notification_badge" id="unread_message">'
										.$num_messages.
									'</span>';
					}
				?>
			</a>
			<!-- NOTIFICATIONS -->
			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
				<i class="fas fa-bell fa-lg"></i>
				<?php
					if ($num_notifications > 0) {
						echo '<span class="notification_badge" id="unread_notification">'
										.$num_notifications.
									'</span>';
					}
				?>
			</a>
			<!-- FRIEND REQUESTS -->
			<a href="requests.php">
				<i class="fas fa-users fa-lg"></i>
			</a>
			<!-- SETTTINGS -->
			<a href="#">
				<i class="fas fa-cog fa-lg"></i>
			</a>
			<!-- LOGOUT -->
			<a href="includes/handlers/logout.php">
				<i class="fas fa-sign-out-alt fa-lg"></i>
			</a>
		</nav>
		<!-- END NAVIGATION -->

		<div class="dropdown_data_window" style="height: 0px; border: none;">
			
		</div>

		<input type="hidden" id="dropdown_data_type" value="" />

	</div>
	<!-- END TOP BAR -->


	<!-- POST LOADING SCRIPT (NEWSFEED) -->
	<script>

		// CREATE USERLOGGEDIN VARIABLE
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';

		// DOCUMENT READY FUNCTION
		$(document).ready(function() {

			// AUTO LOAD POSTS (INFINITE SCROLLING) FUNCTION
			$('.dropdown_data_window').scroll(function() {
				// DROPDOWN_DATA_WINDOW DIV HEIGHT VARIABLE
				var inner_height = $('.dropdown_data_window').innerHeight();
				// SCROLLTOP VARIABLE
				var scroll_top = $('.dropdown_data_window').scrollTop();
				// VARIABLE FOR NEXT PAGE (MORE POSTS)
				var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
				// VARIABLE FOR NO MORE POSTS
				var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

				// CHECK IF THE PAGE IS SCROLLED TO THE BOTTOM OF POSTS_AREA DIV
				// AND THERE ARE ALSO MORE POSTS
				if ((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {

					// VARIABLE TO HOLD NAME OF PAGE TO SEND AJAX REQUESTS TO
					var pageName;
					// GET VALUE OF INPUT WITH ID #DROPDOWN_DATA_TYPE
					var type = $('#dropdown_data_type').val();

					// IF TYPE IS "NOTIIFICATION"
					if (type == 'notification') {
						pageName = 	'ajax_load_notifications.php';
						// IF TYPE IS "MESSAGE"
					} else if (type == 'message') {
						pageName = 'ajax_load_messages.php';
					}

					// VARIABLE OF AJAX REQUEST FOR MORE POSTS
					var ajaxReq = $.ajax({
						url: 'includes/handlers/' + pageName,
						type: 'POST',
						data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
						cache:false,

						success: function(response) {
							// REMOVE CURRENT .NEXTPAGE
							$('.dropdown_data_window').find('.nextPageDropdownData').remove();
							// REMOVE CURRENT NOMORE POSTS
							$('.dropdown_data_window').find('.noMoreDropdownData').remove();

							// LOAD POSTS ONTO POSTS_AREA DIV
							$('.dropdown_data_window').append(response);
						}
					});


				} // END IF

				return false;

			}); // END AUTO LOAD POSTS FUNCTION

		});

	</script>
	<!-- END POST LOADING SCRIPT (NEWSFEED) -->



	<!-- WRAPPER DIV -->
	<div class="wrapper">







