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

<!-- SEARCH RESULTS PANEL -->
<div class="column col-xl-10 col-lg-12 col-md-12 text-center" id="search-results-panel">
	<!-- SEARCH RESULTS TITLE -->
	<div class="col-md-12 text-center">
		<h2>Search Results</h2>
	</div>
	<hr />
	<br />

	<?php
		// IF USER SEARCH QUERY IS EMPTY
		if ($query == '') {
			echo '<p>You must enter something in the search box.</p>';

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
					echo '<h4>'.mysqli_num_rows($usersReturnedQuery).' results found: </h4><br /><br />';
				}
				
				// LOOP WHILE QUERY YEILDS RESULTS
				while ($row = mysqli_fetch_array($usersReturnedQuery)) {
					// CREATE NEW USER OBJECT
					$user_obj = new User($connection, $user['username']);
					// CREATE BLANK VARIABLES FOR $BUTTON & $MUTUAL_FRIENDS
					$button = '';
					$mutual_friends = ''; 

					// IF USER LOGGED IN DID NOT FIND HIS/HER OWN USERNAME
					if ($user['username'] != $row['username']) {

						// IF USER FROM SEARCH IS FRIENDS WITH LOGGED IN USER
						if ($user_obj->isFriend($row['username'])) {
							$button  ='<input type="submit" name="'.$row['username'].'" class="btn btn-danger" value="Remove Friend" />';
							// IF USER LOGGED IN RECIEVED FRIEND REQUEST FROM USER FROM SEARCH
						} else if ($user_obj->didReceiveRequest($row['username'])) {
							$button  ='<input type="submit" name="'.$row['username'].'" class="btn btn-warning" value="Respond to Request" style="color: #FFF;" />';
							// IF USER LOGGED IN SENT FRIEND REQUEST TO USER FROM SEARCH
						} else if ($user_obj->didSendRequest($row['username'])) {
							$button  ='<input type="submit" name="'.$row['username'].'" class="btn btn-secondary" value="Request Sent" />';
							// OPTION FOR USER LOGGED IN TO SEND FRIEND REQUEST TO USER FROM SEARCH
						} else {
							$button  ='<input type="submit" name="'.$row['username'].'" class="btn btn-success" value="Add Friend" />';
						}

						// GET NUMBER OF MUTUAL FRIENDS
						$mutual_friends = $user_obj->getMutualFriends($row['username']).' friends in common';

						// WHEN USER CLICKS ON FRIEND BUTTON
						if (isset($_POST[$row['username']])) {

							// IF ALREADY FRIENDS, REMOVE FRIEND
							if ($user_obj->isFriend($row['username'])) {
								$user_obj->removeFriend($row['username']);
								header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
								// IF USER RECIEVED REQUEST, SENT TO REQUESTS PAGE
							} else if ($user_obj->didReceiveRequest($row['username'])) {
								header('Location: requests.php');
								// IF USER SENT FRIEND REQUEST, 
							} else if ($user_obj->didSendRequest($row['username'])) {
								// MAYBE ADD REMOVE REQUEST FUNCTIONALITY
								// ELSE SEND FRIEND REQUEST
							} else {
								$user_obj->sendRequest($row['username']);
								header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
							}
						}
					}

					// RESULTS STRING
					echo '<div>
									<a href="'.$row['username'].'">
										<img src="'.$row['profile_pic'].'" />
									</a>
									<a href="'.$row['username'].'">
										'.$row['first_name'].' '.$row['last_name'].'
									</a><br />
									<p class="grey-font-two">'.$row['username'].'</p>
									<br />	
									'.$mutual_friends.'
									<form action="" method="POST">
										'.$button.'
									</form>
								</div><br />';

				} // END WHILE LOOP

				// SHOW SEARCH VARIATIONS
				echo '<hr />';
				echo '<p class="grey-font-two">Try searching for: </p>';
				echo '<a href="search.php?q='.$query.'&type=name"> Names</a>,
							<a href="search.php?q='.$query.'&type=username">Usernames</a>';
			}
		}		
	?>
</div>
<!-- END SEARCH RESULTS PANEL -->

</div>
<!-- END WRAPPER DIV -->
</body>
</html>