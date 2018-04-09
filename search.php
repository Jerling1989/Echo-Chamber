<?php
	
	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');

	// CHECK IF QUERY IS SET IN PAGE URL
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	} else {
		$query = '';
	}

	// CHECK IF TYPE IS SET IN PAGE URL
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
	} else {
		$type = 'name';
	}

?>


<div class="main-column column" id="main-column">

	<?php

		// IF USER SEARCH QUERY IS EMPTY
		if ($query == '') {
			echo 'You must enter something in the search box.';

			// ELSE..
		} else {

			// IF QUERY CONTAINS AN UNDERSCORE, ASSUME USER IS SEARCHING FOR USERNAMES
			if ($type == 'username') {
				$usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no'");

				
			} else {

				// SPLIT SEARCH QUERY INTO ARRAY
				$names = explode(' ', $query);

				// IF QUERY CONTAINS THREE WORDS, ASSUME USER IS SEARCHING FOR FIRST, MIDDLE, AND LAST NAME
			  if (count($names) == 3) {
					$usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[2]%') AND user_closed='no'");

				// IF QUERY CONTAINS TWO WORDS, ASSUME USER IS SEARCHING FOR FIRST AND LAST NAME
				} else if (count($names) == 2) {
					$usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no'");

					// IF QUERY CONTAINS ONE WORD ONLY, SEARCH FIRST NAMES OR LAST NAMES
				} else {
					$usersReturnedQuery = mysqli_query($connection, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no'");
				}

				



			}

				

	?>
	
</div>







</body>
</html>