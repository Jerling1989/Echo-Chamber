<?php

	class Notification {
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

		// FUNCTION TO GET NUMBER OF NOTIFICATIONS
		public function getUnreadNumber() {
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// DATABASE QUERY (FIND ALL UNVIEWED NOTIFICATIONS SENT TO LOGGED IN USER)
			$query = mysqli_query($this->connection, "SELECT * FROM notifications WHERE viewed='no' AND user_to='$userLoggedIn'");
			// RETURN NUMBER OF RESULTS FROM QUERY
			return mysqli_num_rows($query);
		}

		// FUNCTION TO INSERT NOTIFICATIONS INTO NOTIFICATIONS TABLE
		public function insertNotification($post_id, $user_to, $type) {
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// GET FIRST AND LAST NAME OF USER LOGGED IN
			$userLoggedInName = $this->user_obj->getFirstAndLastName();
			// SET VARIABLE TO CURRENT TIME
			$date_time = date('Y-m-d H:i:s');

			// SWITCH STATEMENT FOR TYPES OF NOTIFICATIONS
			switch ($type) {
				case 'comment':
					$message = $userLoggedInName . ' commented on your post';
					break;
				case 'like':
					$message = $userLoggedInName . ' liked your post';
					break;
				case 'profile_post':
					$message = $userLoggedInName . ' posted on your profile';
					break;
				case 'comment_non_owner':
					$message = $userLoggedInName . ' commented on a post you commented on';
					break;
				case 'profile_comment':
					$message = $userLoggedInName . ' commented on your profile post';
					break;
			}

			// LINK VARIABLE 
			$link = 'post.php?id=' . $post_id;

			// DATABASE QUERY (INSERT NOTIFICATON DATA INTO NOTIFICATIONS TABLE)
			$insert_query = mysqli_query($this->connection, "INSERT INTO notifications VALUES('', '$user_to', '$userLoggedIn', '$message', '$link', '$date_time', 'no', 'no')");
		}



















	}

?>