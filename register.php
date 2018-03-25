<?php
	// REQUIRE CONFIG.PHP (CONNECTION FILE)
	require 'config/config.php';
	// REQUIRE REGISTER FORM PHP SCRIPT
	require 'includes/form_handlers/register_handler.php';
	// REQUIRE LOGIN FORM PHP SCRIPT
	require 'includes/form_handlers/login_handler.php';
?>


<!DOCTYPE html>
<html>
<head>
	<!-- PAGE TITLE -->
	<title>Echo Chamber | Welcome</title>
	<!-- FAVICON -->
	<link rel="icon" href="assets/img/favicon.ico" type="image/x-icon" />
	<!-- META DATA -->
  <meta charset="utf-8" />
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- RESET CSS LINK -->
  <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
	<!-- REGISTER PAGE CSS LINK -->
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css" />
	<!-- JQUERY CDN LINK -->
	<script
	  src="https://code.jquery.com/jquery-3.3.1.min.js"
	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  crossorigin="anonymous"></script>

</head>


<body id="register-body">

	<!-- SCRIPT TO KEEP SIGN UP FORM OPEN FOR ERROR MESSAGES -->
	<?php
		if(isset($_POST['register_button'])) {
			echo '
				<script>
					$(document).ready(function() {
						$("#first").hide();
						$("#second").show();
					});
				</script>
			';
		}
	?>
	<!-- END SCRIPT TO KEEP SIGN UP FORM OPEN FOR ERROR MESSAGES -->

	<!-- LOGIN PANEL -->
	<div id="login-panel">

		<!-- LOGIN HEADER -->
		<div id="login-header">
			<h1>Swirlfeed!</h1>
			Login or Sign Up below!
		</div>
		<!-- END LOGIN HEADER -->
		<br />

		<!-- LOGIN FORM -->
		<div id="first">
			<form action="register.php" method="POST">
				
				<!-- EMAIL ADDRESS INPUT -->
				<input type="email" name="log_email" placeholder="Email Address" value="<?php
					if (isset($_SESSION['log_email'])) {
						echo $_SESSION['log_email'];
					} ?>" required />
				<br />
				<!-- PASSWORD INPUT -->
				<input type="password" name="log_password" placeholder="Password" required />
				<br />

				<!-- LOGIN ERROR MESSAGE -->
				<?php if (in_array('Email or password was incorrect<br />', $error_array)) {
					echo 'Email or password was incorrect<br />';
				} ?>

				<!-- LOGIN SUBMIT BUTTON -->
				<input type="submit" name="login_button" value="Log In" />
				<br />
				<!-- SIGN UP FORM LINK -->
				<a href="#" id="signup" class="signup">Need an account? Register here!</a>
				<br /><br />

			</form>
		</div>
		<!-- END LOGIN FORM -->

		<!-- SIGN UP FORM -->
		<div id="second">
			<form action="register.php" method="POST">

				<!-- FIRST NAME INPUT -->
				<input type="text" name="reg_fname" placeholder="First Name" value="<?php
					if (isset($_SESSION['reg_fname'])) {
						echo $_SESSION['reg_fname'];
					} ?>" required />
				<br />
				<!-- FIRST NAME ERROR MESSAGE -->
				<?php if (in_array('Your first name must be between 2 and 25 characters<br />', $error_array)) {
					echo 'Your first name must be between 2 and 25 characters<br />';
				} ?>

				<!-- LAST NAME INPUT -->
				<input type="text" name="reg_lname" placeholder="Last Name" value="<?php
					if (isset($_SESSION['reg_lname'])) {
						echo $_SESSION['reg_lname'];
					} ?>" required />
				<br />
				<!-- LAST NAME ERROR MESSAGE -->
				<?php if (in_array('Your last name must be between 2 and 25 characters<br />', $error_array)) {
					echo 'Your last name must be between 2 and 25 characters<br />';
				} ?>

				<!-- EMAIL INPUT -->
				<input type="email" name="reg_email" placeholder="Email Address" value="<?php
					if (isset($_SESSION['reg_email'])) {
						echo $_SESSION['reg_email'];
					} ?>" required />
				<br />

				<!-- EMAIL 2 INPUT -->
				<input type="email" name="reg_email2" placeholder="Confirm Email Address" value="<?php
					if (isset($_SESSION['reg_email2'])) {
						echo $_SESSION['reg_email2'];
					} ?>" required />
				<br />
				<!-- EMAIL ERROR MESSAGES -->
				<?php if (in_array('Email already in use<br />', $error_array)) {
					echo 'Email already in use<br />';
				} else if (in_array('Invalid email format<br />', $error_array)) {
					echo 'Invalid email format<br />';
				} else if (in_array('Your emails do not match<br />', $error_array)) {
					echo 'Your emails do not match<br />';
				} ?>

				<!-- PASSWORD INPUT -->
				<input type="password" name="reg_password" placeholder="Password" required />
				<br />

				<!-- PASSWORD 2 INPUT -->
				<input type="password" name="reg_password2" placeholder="Confirm Password" required />
				<br />
				<!-- PASSWORD ERROR MESSAGES -->
				<?php if (in_array('Your password can only contain english characters or numbers<br />', $error_array)) {
					echo 'Your password can only contain english characters or numbers<br />';
				} else if (in_array('Your password must be between 5 and 30 characters<br />', $error_array)) {
					echo 'Your password must be between 5 and 30 characters<br />';
				} else if (in_array('Your passwords do not match<br />', $error_array)) {
					echo 'Your passwords do not match<br />';
				} ?>

				<!-- SUCCESSFUL SIGN UP MESSAGE -->
				<?php if (in_array("<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br />", $error_array)) {
					echo "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br />";
				} ?>

				<!-- SIGN UP SUBMIT BUTTON -->
				<input type="submit" name="register_button" value="Sign Up" />
				<br />
				
				<!-- SIGN IN FORM LINK -->
				<a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>
				<br /><br />

			</form>
		</div>
		<!-- END SIGN UP FORM -->

	</div>
	<!-- END LOGIN PANEL -->

<!-- CUSTOM JS LINK -->
<script src="assets/js/register.js"></script>

</body>
</html>