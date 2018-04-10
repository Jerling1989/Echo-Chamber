<?php

	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

	// IF USER CLICKS BUTTON TO NOT CLOSE ACCOUNT
	if (isset($_POST['cancel'])) {
		header('Location: settings.php');
	}

	// IF USER CLICKS BUTTON TO CLOSE ACCOUNT
	if(isset($_POST['close_account'])) {
		// DATABASE QUERY (UPDATE USER_CLOSED TO "YES")
		$close_query = mysqli_query($connection, "UPDATE users SET user_closed='yes' WHERE username='$userLoggedIn'");
		// END SESSION AND SEND USER TO REGISTER/LOGIN PAGE
		session_destroy();
		header('Location: register.php');
	}

?>

<div class="main-column column">

	<h4>Close Account</h4>

	Are you sure you want to close your account?<br /><br />
	Closing your account will hide your profile and all your activity from other users.<br /><br />
	You can re-open your account at any time by simply logging in.<br /><br />

	<form action="close_account.php" method="POST">
		<input type="submit" name="close_account" id="close_account" value="Yes! Close it!" class="danger settings_submit" />
		<input type="submit" name="cancel" id="update_details" value="No Way!" class="info settings_submit" />
	</form>

</div>








</div>
<!-- END WRAPPER DIV -->

</body>
</html>