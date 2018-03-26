<?php

	class User {
		private $user;
		private $connection;

		public function __construct($connection. $user) {
			$this->connection = $connection;
			$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$user'");
			$this->user = mysqli_fetch_array($user_details_query);
		}

		public function getFirstAndLastName() {
			$username = $this->user['username'];
			$query = mysqli_query($this->connection, "SELECT first_name, last_name FROM users WHERE username='$username'");
			$row = mysqli_fetch_array($query);
			return $row['first_name'] . ' ' . $row['last_name'];
		}

	}

?>