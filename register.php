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

  <!-- FONT AWESOME -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Contrail+One|Roboto" rel="stylesheet">

  <!-- BOOTSTRAP CDN -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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


	<!-- LOGIN/REGISTER PANEL -->
	<div class="container">
		<div class="row text-center">
			<div id="login-panel" class="col-12 col-sm-10 col-md-8">

				<!-- LOGIN HEADER -->
				<div id="login-header">
					<h1>ECHO CHAMBER</h1>
					<h4>Where People Come to Never Change Their Opinion on Anything!</h4>
					<br /><br />
					<p><i class="fas fa-caret-down fa-lg"></i> Login or Sign Up below! <i class="fas fa-caret-down fa-lg"></i></p>
				</div>
				<!-- END LOGIN HEADER -->
				<br />

				<!-- LOGIN FORM -->
				<div id="first">
					<form action="register.php" method="POST">

						<!-- EMAIL ADDRESS INPUT -->
						<div class="input-group">
					    <input type="email" class="form-control form-control-lg" name="log_email" placeholder="Email Address" value="<?php if (isset($_SESSION['log_email'])) { echo $_SESSION['log_email']; } ?>" required />
					    <div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-at fa-2x"></i>
			          </span>
			        </div>
				    </div>
					  <br />
					  <!-- END EMAIL ADDRESS INPUT -->

						<!-- PASSWORD INPUT -->
						<div class="input-group">
							<input type="password" class="form-control form-control-lg" name="log_password" placeholder="Password" required />
							<div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-key fa-2x"></i>
			          </span>
			        </div>
						</div>
					  <br />
					  <!-- END PASSWORD INPUT -->

					  <!-- LOGIN ERROR MESSAGE -->
						<?php if (in_array('email/password error', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">email or password was incorrect</div><br />';
						} ?>

						<!-- LOGIN SUBMIT BUTTON -->		  
					  <button type="submit" class="btn btn-primary btn-lg" name="login_button">
					  	Log In
					  </button>
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
						<div class="input-group">
							<input type="text" class="form-control form-control-lg" name="reg_fname" placeholder="First Name" value="<?php if (isset($_SESSION['reg_fname'])) { echo $_SESSION['reg_fname']; } ?>" required />
							<div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-user fa-2x"></i>
			          </span>
			        </div>
						</div>
						<br />
						<!-- END FIRST NAME INPUT -->

						<!-- FIRST NAME ERROR MESSAGE -->
						<?php if (in_array('first name length', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">your first name must be between 2 and 25 characters</div><br />';
						} ?>

						<!-- LAST NAME INPUT -->
						<div class="input-group">
							<input type="text" class="form-control form-control-lg" name="reg_lname" placeholder="Last Name" value="<?php if (isset($_SESSION['reg_lname'])) { echo $_SESSION['reg_lname']; } ?>" required />
							<div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-user fa-2x"></i>
			          </span>
			        </div>
						</div>
						<br />
						<!-- END LAST NAME INPUT -->

						<!-- LAST NAME ERROR MESSAGE -->
						<?php if (in_array('last name length', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">your last name must be between 2 and 25 characters</div><br />';
						} ?>

						<!-- EMAIL 1 INPUT -->
						<div class="input-group">
							<input type="email" class="form-control form-control-lg" name="reg_email" placeholder="Email Address" value="<?php if (isset($_SESSION['reg_email'])) { echo $_SESSION['reg_email']; } ?>" required />
							<div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-at fa-2x"></i>
			          </span>
			        </div>
						</div>
						<br />
						<!-- END EMAIL 1 INPUT -->

						<!-- EMAIL 2 INPUT -->
						<div class="input-group">
							<input type="email" class="form-control form-control-lg" name="reg_email2" placeholder="Confirm Email Address" value="<?php if (isset($_SESSION['reg_email2'])) { echo $_SESSION['reg_email2']; } ?>" required />
							<div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-at fa-2x"></i>
			          </span>
			        </div>
						</div>
						<br />
						<!-- END EMAIL 2 INPUT -->

						<!-- EMAIL ERROR MESSAGES -->
						<?php if (in_array('email in use', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">email already in use</div><br />';
						} else if (in_array('invalid format', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">invalid email format</div><br />';
						} else if (in_array('emails do not match', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">your emails do not match</div><br />';
						} ?>

						<!-- PASSWORD 1 INPUT -->
						<div class="input-group">
							<input type="password" class="form-control form-control-lg" name="reg_password" placeholder="Password" required />
							<div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-key fa-2x"></i>
			          </span>
			        </div>
						</div>
						<br />
						<!-- END PASSWORD 1 INPUT -->

						<!-- PASSWORD 2 INPUT -->
						<div class="input-group">
							<input type="password" class="form-control form-control-lg" name="reg_password2" placeholder="Confirm Password" required />
							<div class="input-group-append">
			          <span class="input-group-text" id="inputGroupAppend">
			          	<i class="fas fa-key fa-2x"></i>
			          </span>
			        </div>
						</div>
						<br />
						<!-- END PASSWORD 2 INPUT -->

						<!-- PASSWORD ERROR MESSAGES -->
						<?php if (in_array('password characters', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">your password can only contain english characters or numbers</div><br />';
						} else if (in_array('password length', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">your password must be between 5 and 30 characters</div><br />';
						} else if (in_array('passwords do not match', $error_array)) {
							echo '<div class="alert alert-danger" role="alert">your passwords do not match</div><br />';
						} ?>

						<!-- SUCCESSFUL SIGN UP MESSAGE -->
						<?php if (in_array('successful signup', $error_array)) {
							echo '<div class="alert alert-success" role="alert">You\'re all set! Go ahead and login!</span></div><br />';
						} ?>

						<!-- SIGN UP SUBMIT BUTTON -->
						<button type="submit" class="btn btn-primary btn-lg" name="register_button">
							Sign Up
						</button>
						<br />
						
						<!-- SIGN IN FORM LINK -->
						<a href="#" id="signin" class="signin">Already have an account? Log in here!</a>
						<br /><br />

					</form>
				</div>
				<!-- END SIGN UP FORM -->

			</div>
		</div>
	</div>
	<!-- END LOGIN/REGISTER INFO PANEL -->


<!-- CUSTOM JS LINK -->
<script src="assets/js/register.js"></script>

</body>
</html>