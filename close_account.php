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

<!-- CLOSE ACCOUNT PANEL -->
<div class="column col-xl-10 col-lg-12 col-md-12" id="close-account">
	<!-- CLOSE ACCOUNT TITLE -->
	<div class="text-center">
		<h2>Close Account</h2>
	</div>
	<hr />
	<br />
	<!-- CLOSE ACCOUNT INSTRUCTIONS -->
	<p>Are you sure you want to close your account?</p>
	<p>Closing your account will hide your profile and all your activity from other users.</p>
	<p>You can re-open your account at any time by simply logging in.</p>
	<!-- CLOSE ACCOUNT FORM -->
	<form action="close_account.php" method="POST">
		<!-- CONFIRM ACCOUNT CLOSURE -->
		<input type="submit" name="close_account" id="close_account" value="Yes! Close it!" class="btn btn-danger" />
		<!-- DENY ACCOUNT CLOSURE -->
		<input type="submit" name="cancel" id="update_details" value="No Way!" class="btn btn-success" />
	</form>
	<!-- END CLOSE ACCOUNT BUTTON -->
</div>
<!-- END CLOSE ACCOUNT PANEL -->

</div>
<!-- END WRAPPER DIV -->
</body>
</html>