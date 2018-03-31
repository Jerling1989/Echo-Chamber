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

		
		


	}

?>