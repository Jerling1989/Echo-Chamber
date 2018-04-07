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




	}

?>