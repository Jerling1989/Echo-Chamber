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
		public function getMessages() {
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			$data = '';
			// DATABASE QUERY (UPDATE OPENED TO "YES")
			$query = mysqli_query($this->connection, "UPDATE messages SET opened='yes' WHERE user_to='$userLoggedIn' AND user_from='$otherUser'");
			// DATABASE QUERY (LOAD MESSAGES BETWEEN TWO USERS)
			$get_messages_query = mysqli_query($this->connection, "SELECT * FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$otherUser') OR (user_to='$otherUser' AND user_from='$userLoggedIn')");
		}



	}

?>