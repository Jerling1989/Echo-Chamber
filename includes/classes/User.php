<?php

	class User {
		// PRIVATE VARIABLES
		private $user;
		private $connection;

		// CREATE PUBLIC VARIABLES
		public function __construct($connection, $user) {
			// CONNECTION VARIABLE
			$this->connection = $connection;
			// DATABASE QUERY
			$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$user'");
			// QUERY RESULT ARRAY
			$this->user = mysqli_fetch_array($user_details_query);
		}

		// GET USERNAME FUNCTION
		public function getUsername() {
			// RETURN USERNAME
			return $this->user['username'];
		}

		// GET NUMBER OF POSTS FUNCTION
		public function getNumPosts() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (NUMBER OF POSTS)
			$query = mysqli_query($this->connection, "SELECT num_posts FROM users WHERE username='$username'");
			// STORE NUMBER OF POSTS IN ARRAY
			$row = mysqli_fetch_array($query);
			// RETURN NUMBER OF POSTS
			return $row['num_posts'];
		}

		// GET FIRST AND LAST NAME FUNCTION
		public function getFirstAndLastName() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (FIRST AND LAST NAME)
			$query = mysqli_query($this->connection, "SELECT first_name, last_name FROM users WHERE username='$username'");
			// STORE FIRST AND LAST NAME IN ARRAY
			$row = mysqli_fetch_array($query);
			// RETURN FIRST AND LAST NAME
			return $row['first_name'] . ' ' . $row['last_name'];
		}

		// GET PROFILE PICTURE FUNCTION
		public function getProfilePic() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (PROFILE PIC)
			$query = mysqli_query($this->connection, "SELECT profile_pic FROM users WHERE username='$username'");
			// STORE FIRST AND LAST NAME IN ARRAY
			$row = mysqli_fetch_array($query);
			// RETURN FIRST AND LAST NAME
			return $row['profile_pic'];
		}

		// GET FRIEND ARRAY FUNCTION
		public function getFriendArray() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (FRIEND ARRAY)
			$query = mysqli_query($this->connection, "SELECT friend_array FROM users WHERE username='$username'");
			// STORE FRIEND ARRAY IN ARRAY
			$row = mysqli_fetch_array($query);
			// RETURN FRIEND ARRAY
			return $row['friend_array'];
		}

		// GET USER ACCOUNT CLOSED DATA FUNCTION
		public function isClosed() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (ACCOUNT CLOSED)
			$query = mysqli_query($this->connection, "SELECT user_closed FROM users WHERE username='$username'");
			// STORE ACCOUNT CLOSED INFO IN ARRAY
			$row = mysqli_fetch_array($query);

			// CHECK IF USER ACCOUNT IS CLOSED
			if ($row['user_closed'] == 'yes') {
				return true;
			} else {
				return false;
			}
		}

		// CHECK IF USER IS FRIENDS WITH ANOTHER USER
		public function isFriend($username_to_check) {
			$usernameComma = ',' . $username_to_check . ',';

			// CHECK IF USER IS IN FRIEND_ARRAY OR IF USER IS YOURSELF
			if (strstr($this->user['friend_array'], $usernameComma) || $username_to_check == $this->user['username']) {
				return true;
			} else {
				return false;
			}
		}

		// CHECK IF USER RECIEVED FRIEND REQUEST
		public function didReceiveRequest($user_from) {
			// USER_FROM VARIABLE
			$user_to = $this->user['username'];
			// DATABASE QUERY (FRIEND REQUEST)
			$check_request_query = mysqli_query($this->connection, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from ='$user_from'");

			// CHECK IF QUERY RETURNED RESULTS
			if (mysqli_num_rows($check_request_query) > 0) {
				return true;
			} else {
				return false;
			}
		}

		// CHECK IF USER SENT FRIEND REQUEST
		public function didSendRequest($user_to) {
			// USER_TO VARIABLE
			$user_from = $this->user['username'];
			// DATABASE QUERY (FRIEND REQUEST)
			$check_request_query = mysqli_query($this->connection, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from ='$user_from'");

			// CHECK IF QUERY RETURNED RESULTS
			if (mysqli_num_rows($check_request_query) > 0) {
				return true;
			} else {
				return false;
			}
		}

		// FUNCTION TO REMOVE FRIEND
		public function removeFriend($user_to_remove) {
			// $LOGGED_IN_USER VARIABLE
			$logged_in_user = $this->user['username'];
			// DATABASE QUERY (USER FRIEND ARRAY)
			$query = mysqli_query($this->connection, "SELECT friend_array FROM users WHERE username='$user_to_remove'");
			// STORE QUERY RESULTS IN ARRAY
			$row = mysqli_fetch_array($query);
			// STORE USER FRIEND ARRAY INFO IN VARIABLE
			$friend_array_username = $row['friend_array'];

			// REMOVE FRIEND FROM LOGGED IN USERS FRIEND ARRAY
			$new_friend_array = str_replace($user_to_remove . ',', '', $this->user['friend_array']);
			// UPDATE NEW ARRAY INTO LOGGED IN USERS DATABASE
			$remove_friend = mysqli_query($this->connection, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$logged_in_user'");

			// REMOVE FRIEND FROM USER PROFILE FRIEND ARRAY
			$new_friend_array = str_replace($this->user['username'] . ',', '', $friend_array_username);
			// UPDATE NEW ARRAY INTO USER PROFILE DATABASE
			$remove_friend = mysqli_query($this->connection, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$user_to_remove'");
		}

		// FUNCTION TO SEND FRIEND REQUEST
		public function sendRequest($user_to) {
			// $USER_FROM VARIABLE
			$user_from = $this->user['username'];
			// DATABASE QUERY (SEND REQUEST DATA INTO FRIEND_REQUESTS TABLE)
			$query = mysqli_query($this->connection, "INSERT INTO friend_requests VALUES('', '$user_to', '$user_from')");
		}

		// CALCULATE MUTUAL FRIENDS FUNCTION
		public function getMutualFriends($user_to_check) {
			// $MUTUALFRIENDS VARIABLE
			$mutualFriends = 0;
			// SET $USER_ARRAY VARIABLE
			$user_array = $this->user['friend_array'];
			// SPLIT $USER_ARRAY VARIABLE AT COMMA
			$user_array_explode = explode(',', $user_array);

			// DATABASE QUERY (FRIEND ARRAY OF $USER_TO_CHECK)
			$query = mysqli_query($this->connection, "SELECT friend_array FROM users WHERE username='$user_to_check'");
			// STORE QUERY RESULTS IN ARRAY
			$row = mysqli_fetch_array($query);
			// SET $USER_TO_CHECK_ARRAY VARIABLE FROM FRIEND ARRAY
			$user_to_check_array = $row['friend_array'];
			// SPLIT $USER_TO_CHECK_ARRAY VARIABLE AT COMMA
			$user_to_check_array_explode = explode(',', $user_to_check_array);

			// LOOP THROUGH BOTH FRIEND ARRAYS AND ADD 1 TO $MUTUALFRIENDS WHEN THEY MATCH
			foreach($user_array_explode as $i) {
				foreach($user_to_check_array_explode as $j) {
					if ($i == $j && $i != '') {
						$mutualFriends++;
					}
				}
			}
			// RETURN VALUE OF MUTAL FRIENDS
			return $mutualFriends;
		}



	}

?>