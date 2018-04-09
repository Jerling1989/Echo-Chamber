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

<!-- MAIN COLUMN -->
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

				
				// IF QUERY RETURNS NO RESULTS, SHOW MESSAGE OF NO RESULTS
				if (mysqli_num_rows($usersReturnedQuery) == 0) {
					echo "We can't find anyone with a ".$type." like: ".$query;
					// ELSE, SHOW THE NUMBER OF RESULTS QUERY FOUND
				} else {
					echo mysqli_num_rows($usersReturnedQuery) . " results found: <br /><br />";
				}
				// SHOW SEARCH VARIATIONS
				echo '<p class="grey-font">Try searching for:</p>';
				echo '<a href="search.php?q='.$query.'&type=name">Names</a>,
							<a href="search.php?q='.$query.'&type=username">Usernames</a><hr />';

				// LOOP WHILE QUERY YEILDS RESULTS
				while ($row = mysqli_fetch_array($usersReturnedQuery)) {
					// CREATE NEW USER OBJECT
					$user_obj = new User($connection, $user['username']);

					// CREATE BLANK VARIABLES FOR $BUTTON & $MUTUAL_FRIENDS
					$button = '';
					$mutual_friends = ''; 

					// IF USER DID NOT FIND HIS/HER OWN USERNAME
					if ($user['username'] != $row['username']) {

					}
				}


			}

		}		

	?>
	
</div>
<!-- END MAIN COLUMN -->


</div>
<!-- END WRAPPER DIV -->
</body>
</html>