<?php

	class Message {
		// PRIVATE VARIABLES
		private $user_obj;
		private $connection;

		// CREATE PUBLIC VARIABLES
		public function __construct($connection, $user) {
			// CONNECTION VARIABLE
			$this->connection = $connection;
			// CREATE NEW USER OBJECT
			$this->user_obj = new User($connection, $user);
		}

		// FUNCTION TO GET MOST RECENT USER MESSAGED
		public function getMostRecentUser() {
			// CREATE $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// DATABASE QUERY (FIND 1 MESSAGE WHERE USER_TO OR USER_FROM IS $USERLOGGEDIN)
			$query = mysqli_query($this->connection, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC LIMIT 1");

			// IF THERE ARE NO RESULTS FROM QUERY RETURN FALSE
			if (mysqli_num_rows($query) == 0) {
				return false;
			}

			// STORE QUERY RESULTS IN ARRAY
			$row = mysqli_fetch_array($query);
			// SET $USER_TO VARIABLE
			$user_to = $row['user_to'];
			// SET $USER_FROM VARIABLE
			$user_from = $row['user_from'];

			// IF $USER_TO IS NOT THE $USERLOGGEDIN RETURN $USER_TO
			if ($user_to != $userLoggedIn) {
				return $user_to;
				// ELSE RETURN $USER_FROM
			} else {
				return $user_from;
			}

		}

		// FUNCTION TO SEND NEW MESSAGE
		public function sendMessage($user_to, $body, $date) {
			// IF BODY OF MESSAGE IS NOT BLANK
			if ($body != '') {
				// SET $USERLOGGEDIN VARIABLE
				$userLoggedIn = $this->user_obj->getUsername();
				// DATABASE QUERY (INSERT MESSAGE INTO MESSAGES TABLE)
				$query = mysqli_query($this->connection, "INSERT INTO messages VALUES('', '$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
			}
		}

		// FUNCTION TO RETRIEVE MESSAGES
		public function getMessages($otherUser) {
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// CREATE EMPTY $DATA VARIABLE
			$data = '';
			// DATABASE QUERY (UPDATE OPENED TO "YES")
			$query = mysqli_query($this->connection, "UPDATE messages SET opened='yes' WHERE user_to='$userLoggedIn' AND user_from='$otherUser'");
			// DATABASE QUERY (FIND MESSAGES BETWEEN TWO USERS)
			$get_messages_query = mysqli_query($this->connection, "SELECT * FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$otherUser') OR (user_to='$otherUser' AND user_from='$userLoggedIn')");

			// WHILE DATABASE QUERY YEILDS RESULTS
			while ($row = mysqli_fetch_array($get_messages_query)) {
				// SET VARIABLES
				$user_to = $row['user_to'];
				$user_from = $row['user_from'];
				$body = $row['body'];

				// CONDITIONAL STATEMENT TO SET $DIV_TOP VARIABLE WITH CORRESPONDING DIV ID
				$div_top = ($user_to == $userLoggedIn) ? '<div class="message" id="blue">' : '<div class="message" id="green">';
				// CONCATENATE MESSAGE INFO TO $DATA VARIABLE
				$data = $data . $div_top . $body . '</div><br /><br />';
			}
			// RETURN $DATA VARIABLE
			return $data;
		}

		// FUNCTION TO GET LATEST MESSAGE BETWEEN USERS
		public function getLatestMessage($userLoggedIn, $username) {
			// CREATE EMPTY ARRAY FOR THE $DETAILS_ARRAY VARIABLE
			$details_array = array();

			// DATABASE QUERY (FIND 1 MESSAGE BETWEEN BOTH USERS)
			$query = mysqli_query($this->connection, "SELECT body, user_to, date FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$username') OR (user_to='$username' AND user_from='$userLoggedIn') ORDER BY id DESC LIMIT 1");

			// STORE QUERY RESULTS INTO $ROW ARRAY
			$row = mysqli_fetch_array($query);
			// CONDITIONAL STATEMENT TO SET $SENT_BY TO PROPER STRING
			$sent_by = ($row['user_to'] == $userLoggedIn) ? 'They said: ' : 'You said: ';

			// CURRENT TIME
			$date_time_now = date('Y-m-d H:i:s');
			// DATE POST WAS ADDED VARIABLE
			$start_date = new DateTime($row['date']);
			// CURRENT DATE VARIABLE
			$end_date = new DateTime($date_time_now);
			// DIFFERENCE BETWEEN BOTH DATE VARIABLES
			$interval = $start_date->diff($end_date);
			// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE YEAR
			if ($interval->y >= 1) {
				if($interval == 1) {
					$time_message = $interval->y . ' year ago'; // ONE YEAR
				} else {
					$time_message = $interval->y . ' years ago'; // MULTIPLE YEARS
				}
				// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE MONTH
			} else if ($interval->m >= 1) {
				if ($interval->d == 0) {
					$days = ' ago'; // NO ADDITIONAL DAYS
				} else if ($interval->d == 1) {
					$days = $interval->d . ' day ago'; // ONE ADDITIONAL DAYS
				} else {
					$days = $interval->d . ' days ago'; // MULTIPLE ADDITIONAL DAYS
				}

				if($interval->m == 1) {
					$time_message = $interval->m . ' month ago' . $days; // ONE MONTH PLUS DAYS
				} else {
					$time_message = $interval->m . ' months ago' . $days; // MULTIPLE MONTHS PLUS DAYS
				}
				// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE DAY
			} else if ($interval->d >= 1) {
				if ($interval->d == 1) {
					$time_message = 'Yesterday';
				} else {
					$time_message = $interval->d . ' days ago';
				}
				// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE HOUR
			} else if ($interval->h >= 1) {
				if ($interval->h == 1) {
					$time_message = $interval->h . ' hour ago'; // ONE HOUR
				} else {
					$time_message = $interval->h . ' hours ago'; // MULTIPLE HOURS
				}
				// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE MINUTE
			} else if ($interval->i >= 1) {
				if ($interval->i == 1) {
					$time_message = $interval->i . ' minute ago'; // ONE MINUTE
				} else {
					$time_message = $interval->i . ' minutes ago'; // MULTIPLE MINUTES
				}
				// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE SECOND
			} else {
				if ($interval->s < 30) {
					$time_message = 'Just now'; // LESS THAN 30 SECONDS
				} else {
					$time_message = $interval->s . ' seconds ago'; // OVER 30 SECONDS
				}
			}

			// PUSH MESSAGE DETAILS INTO $DETAILS_ARRAY
			array_push($details_array, $sent_by);
			array_push($details_array, $row['body']);
			array_push($details_array, $time_message);

			// RETURN $DETAILS_ARRAY
			return $details_array;
		}

		// FUNCTION TO LOAD CONVERSATION LIST
		public function getConvos() {
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// CREATE EMPTY $RETURN_STRING VARIABLE
			$return_string = '';
			// CREATE EMPTY ARRAY FOR $CONVOS VARIABLE
			$convos = array();

			// DATABASE QUERY (FIND USERNAMES OF PEOPLE IN CONVERSATIONS)
			$query = mysqli_query($this->connection, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC");

			// WHILE DATABASE QUERY YEILDS RESULTS
			while ($row = mysqli_fetch_array($query)) {
				// CONDITIONAL TO SET $USER_TO_PUSH VARIABLE TO PROPER USER
				$user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

				// CHECK THAT USER IS NOT ALREADY IN ARRAY
				if (!in_array($user_to_push, $convos)) {
					// PUSH USER INTO ARRAY
					array_push($convos, $user_to_push);
				}
			}

			// FOR EACH ITERATION OF THE $CONVOS ARRAY SET $USERNAME VARIABLE
			foreach ($convos as $username) {
				// CREATE NEW USER OBJECT
				$user_found_obj = new User($this->connection, $username);
				// CREATE VARIABLE OF LATEST MESSAGE BETWEEN USERS
				$latest_message_details = $this->getLatestMessage($userLoggedIn, $username);

				// CONDITIONAL STATEMENT TO SET $DOTS VARIABLE TO ELLIPSIS 
				// IF MESSAGE LENGTH IS LONGER THAN OR EQUAL TO 12 CHARACTERS
				$dots = (strlen($latest_message_details[1]) >= 12) ? '...' : '';
				// END THE $SPLIT VARIABLE AFTER 12 CHARACTERS
				$split = str_split($latest_message_details[1], 12);
				// CONCATENATE $DOTS TO END OF $SPLIT VARIABLE
				$split = $split[0] . $dots;

				// ADD MESSAGE DETAILS TO $RETURN_STRING VARIABLE
				$return_string .= '<a href="messages.php?u='.$username.'">
													 	<div class="user_found_messages">
														 	<img src="'.$user_found_obj->getProfilePic().'" style="border-radius: 5px; margin-right: 5px;" />'
														 	.$user_found_obj->getFirstAndLastName().'
														 	<span class="timestamp_smaller grey-font">'
														 		.$latest_message_details[2]. 
														 	'</span>
														 	<p class="grey-font" style="margin: 0;">'
														 		.$latest_message_details[0].$split. 
														 	'</p>
														</div>
													 </a>';
			}

			// RETURN $RETURN_STRING
			return $return_string;
			
		}

		// FUNCTION TO LOAD MESSAGES FOR DROPDOWN MENU
		public function getConvosDropdown($data, $limit) {
			// SET $PAGE VARIABLE
			$page = $data['page'];
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// CREATE EMPTY $RETURN_STRING VARIABLE
			$return_string = '';
			// CREATE EMPTY ARRAY FOR $CONVOS VARIABLE
			$convos = array();

			// IF STATEMENT TO TELL WHEN TO LOAD POSTS
			if ($page == 1) {
				$start = 0;
			} else {
				$start = ($page - 1) * $limit;
			}

			// DATABASE QUERY (CHANGE VIEWED TO "YES" IN MESSAGES TABLE)
			$set_viewed_query = mysqli_query($this->connection, "UPDATE messages SET viewed='yes' WHERE user_to='$userLoggedIn'");

			// DATABASE QUERY (FIND USERNAMES OF PEOPLE IN CONVERSATIONS)
			$query = mysqli_query($this->connection, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC");

			// WHILE DATABASE QUERY YEILDS RESULTS
			while ($row = mysqli_fetch_array($query)) {
				// CONDITIONAL TO SET $USER_TO_PUSH VARIABLE TO PROPER USER
				$user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

				// CHECK THAT USER IS NOT ALREADY IN ARRAY
				if (!in_array($user_to_push, $convos)) {
					// PUSH USER INTO ARRAY
					array_push($convos, $user_to_push);
				}
			}

			$num_iterations = 0; // NUMBER OF MESSAGES CHECKED
			$count = 1; // NUMBER OF MESSAGES POSTED

			// FOR EACH ITERATION OF THE $CONVOS ARRAY SET $USERNAME VARIABLE
			foreach ($convos as $username) {

				// IF IT HASN'T REACHED START POINT CONTINUE
				if ($num_iterations++ < $start) {
					continue;
				}

				// IF NUMBER OF MESSAGES EXCEEDS LIMIT, BREAK LOOP
				if ($count > $limit) {
					break;
				} else {
					$count++;
				}

				// DATABASE QUERY (FIND OPENED MESSAGED BETWEEN USERS)
				$is_unread_query = mysqli_query($this->connection, "SELECT opened FROM messages WHERE user_to='$userLoggedIn' AND user_from='$username' ORDER BY id DESC");
				// STORE QUERY RESULTS IN $ROW ARRAY
				$row = mysqli_fetch_array($is_unread_query);
				// CONDITIONAL TO STORE DIFFERENT BACKGROUND-COLOR IN $STYLE VARIABLE
				// TO DISTINGUISH BETWEEN OPENED AND UNOPENED MESSAGES
				$style = ($row['opened'] == 'no') ? "background-color: #DDEDFF;" : "";

				// CREATE NEW USER OBJECT
				$user_found_obj = new User($this->connection, $username);
				// CREATE VARIABLE OF LATEST MESSAGE BETWEEN USERS
				$latest_message_details = $this->getLatestMessage($userLoggedIn, $username);

				// CONDITIONAL STATEMENT TO SET $DOTS VARIABLE TO ELLIPSIS 
				// IF MESSAGE LENGTH IS LONGER THAN OR EQUAL TO 12 CHARACTERS
				$dots = (strlen($latest_message_details[1]) >= 12) ? '...' : '';
				// END THE $SPLIT VARIABLE AFTER 12 CHARACTERS
				$split = str_split($latest_message_details[1], 12);
				// CONCATENATE $DOTS TO END OF $SPLIT VARIABLE
				$split = $split[0] . $dots;

				// ADD MESSAGE DETAILS TO $RETURN_STRING VARIABLE
				$return_string .= '<a href="messages.php?u='.$username.'">
													 	<div class="user_found_messages" style="'.$style.'">
														 	<img src="'.$user_found_obj->getProfilePic().'" style="border-radius: 5px; margin-right: 5px;" />'
														 	.$user_found_obj->getFirstAndLastName().'
														 	<span class="timestamp_smaller grey-font">'
														 		.$latest_message_details[2]. 
														 	'</span>
														 	<p class="grey-font" style="margin: 0;">'
														 		.$latest_message_details[0].$split. 
														 	'</p>
														</div>
													 </a>';
			}

			// IF POSTS WERE LOADED
			if ($count > $limit) {
				$return_string .= "<input type='hidden' class='nextPageDropdownData' value='".($page+1)."' />
													 <input type='hidden' class='noMoreDropdownData' value='false' />";
			} else {
				$return_string .= "<input type='hidden' class='noMoreDropdownData' value='true' />
													 <p style='text-align: center;'>No More Messages to Load</p>";
			}

			// RETURN $RETURN_STRING
			return $return_string;
		}

		// FUNCTION TO GET NUMBER OF MESSAGE NOTIFICATIONS
		public function getUnreadNumber() {
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// DATABASE QUERY (FIND ALL UNVIEWED MESSAGES SENT TO LOGGED IN USER)
			$query = mysqli_query($this->connection, "SELECT * FROM messages WHERE viewed='no' AND user_to='$userLoggedIn'");
			// RETURN NUMBER OF RESULTS FROM QUERY
			return mysqli_num_rows($query);
		}



	}

?>