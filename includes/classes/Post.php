<?php

	class Post {
		// PRIVATE VARIABLES
		private $user_obj;
		private $connection;

		// CREATE PUBLIC VARIABLES
		public function __construct($connection, $user) {
			// CONNECTION VARIABLE
			$this->connection = $connection;
			// CREATE USER_OBJ VARIABLE FROM USER CLASS
			$this->user_obj = new User($connection, $user);
		}

		// FUNCTION TO SUBMIT USER POST
		public function submitPost($body, $user_to) {
			// REMOVE ANY HTML TAGS
			$body = strip_tags($body);
			// ESCAPE CHARACTERS THAT MAY CAUSE ISSUES (SINGLE QUOTE)
			$body = mysqli_real_escape_string($this->connection, $body);
			// DELETES ALL SPACES
			$check_empty = preg_replace('/\s+/', '', $body);

			// CHECK THAT POST HAS CONTENT
			if ($check_empty != '') {
				// CURRENT DATE AND TIME
				$date_added = date('Y-m-d H:i:s');
				// GET USERNAME
				$added_by = $this->user_obj->getUsername();

				// IF USER IS ON PROFILE, USER_TO IS 'NONE'
				if ($user_to == $added_by) {
					$user_to = 'none';
				}
			}
		}

	}

?>