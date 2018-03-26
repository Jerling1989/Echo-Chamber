<?php

	class User {
		// PRIVATE VARIABLES
		private $user;
		private $connection;

		// CREATE PUBLIC VARIABLES
		public function __construct($connection. $user) {
			// CONNECTION VARIABLE
			$this->connection = $connection;
			// DATABASE QUERY
			$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$user'");
			// QUERY RESULT ARRAY
			$this->user = mysqli_fetch_array($user_details_query);
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

	}

?>