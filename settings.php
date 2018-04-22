<?php
	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');
	include('includes/form_handlers/settings_handler.php');
?>

<!-- USER SETTINGS PANEL -->
<div class="column col-xl-10 col-lg-12 col-md-12" id="settings-panel">
	<!-- ACCOUNT SETTINGS TITLE -->
	<div class="col-md-12 text-center">
		<h2>Account Settings</h2>
	</div>
	<hr />
	<br />

	<!-- CHANGE PROFILE PIC PANEL -->
	<div class="col-lg-6 col-md-8 col-sm-10 col-12 change-user-settings">
		<!-- CHANGE PROFILE PIC TITLE -->
		<h4>Change Profile Picture</h4>
		<hr />
		<!-- CURRENT USER PROFILE PIC -->
		<div class="text-center" id="inside-form">
			<?php
				echo '<img src="'.$user['profile_pic'].'" class="img-fluid" />';
			?>
			<br /><br /><br />
			<!-- LINK TO UPLOAD NEW PROFILE PIC -->
			<a href="upload.php">
				<button class="btn btn-outline-light">Choose New Picture </button>
			</a>
		</div>
		<br />
	</div>
	<!-- END CHANGE PROFILE PIC PANEL -->

	<br /><br />

	<?php
		$user_data_query = mysqli_query($connection, "SELECT first_name, last_name, email, username FROM users WHERE username='$userLoggedIn'");
		$row = mysqli_fetch_array($user_data_query);

		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$email = $row['email'];
		$username = $row['username'];
	?>

	<!-- CHANGE USER DETAILS PANEL -->
	<div class="col-lg-6 col-md-8 col-sm-10 col-12 change-user-settings">
		<!-- CHANGE DETAILS TITLE -->
		<h4>Change User Details</h4>
		<hr />
		<!-- CHANGE DETAILS FORM -->
		<form action="settings.php" method="POST">
			<!-- FIRST NAME -->
			<div class="form-group">
				<label for="first_name">First Name</label>
				<input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />
			</div>
			<!-- LAST NAME -->
			<div class="form-group">
				<label for="last_name">Last Name</label>
				<input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>"/>
			</div>
			<!-- EMAIL -->
			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" />
			</div>
			<!-- USERNAME -->
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" />
			</div>
			<br />

			<!-- ERROR/SUCCESS MESSAGE -->
			<?php echo $message; ?>

			<!-- SUBMIT BUTTON -->
			<div class="text-center">
				<input type="submit" name="update_details" id="save_details" value="Update Details" class="btn btn-outline-light" />
			</div>
			<br />
		</form>
		<!-- END CHANGE DETAILS FORM -->
	</div>
	<!-- END CHANGE USER DETAILS PANEL -->

	<br /><br />

	<!-- CHANGE USER PASSWORD PANEL -->
	<div class="col-lg-6 col-md-8 col-sm-10 col-12 change-user-settings">
		<!-- CHANGE PASSWORD TITLE -->
		<h4>Change Password</h4>
		<hr />
		<!-- CHANGE PASSWORD FORM -->
		<form action="settings.php" method="POST">
			<!-- OLD PASSWORD -->
			<div class="form-group">
				<label>Old Password</label>
				<input type="password" class="form-control" id="old_password" name="old_password" />
			</div>
			<!-- NEW PASSWORD -->
			<div class="form-group">
				<label for="new_password_1">New Password</label>
				<input type="password" class="form-control" id="new_password_1" name="new_password_1" />
			</div>
			<!-- NEW PASSWORD CONFIRMATION -->
			<div class="form-group">
				<label for="new_password_2">Confirm New Password</label>
				<input type="password" class="form-control" id="new_password_2" name="new_password_2" />
			</div>
			<br />

			<!-- ERROR/SUCCESS MESSAGE -->
			<?php echo $password_message; ?>

			<!-- SUBMIT BUTTON -->
			<div class="text-center">
				<input type="submit" name="update_password" id="save_details" value="Update Password" class="btn btn-outline-light" />
			</div>
			<br />
		</form>
		<!-- END CHANGE PASSWORD FORM -->
	</div>
	<!-- END CHANGE USER PASSWORD PANEL -->

	<br /><br />

	<!-- CLOSE ACCOUNT PANEL -->
	<div class="col-lg-6 col-md-8 col-sm-10 col-12 change-user-settings">
		<!-- CLOSE ACCOUNT TITLE -->
		<h4>Close Account</h4>
		<hr />
		<!-- CLOSE ACCOUNT FORM -->
		<form action="settings.php" method="POST">
			<!-- SUBMIT BUTTON -->
			<div class="text-center">
				<input type="submit" name="close_account" id="close_account" value="Close Account" class="btn btn-danger" />
			</div>
		</form>
	</div>
	<!-- END CLOSE ACCOUNT PANEL -->
	<br /><br />
</div>
<!-- END USER SETTINGS PANEL -->

</div>
<!-- END WRAPPER DIV -->
</body>
</html>