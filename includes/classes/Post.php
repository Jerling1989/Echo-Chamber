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

				// INSERT POST INTO DATABASE
				$query = mysqli_query($this->connection, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
				// RETURN ID OF POST SUBMITTED
				$return_id = mysqli_insert_id($this->connection);

				// INSERT NOTIFICATION


				// GET NUMBER OF POSTS FROM DATABASE
				$num_posts = $this->user_obj->getNumPosts();
				// ADD 1 TO NUMBER OF POSTS 
				$num_posts++;
				// UPDATE NUMBER OF POSTS BACK INTO DATABASE
				$update_query = mysqli_query($this->connection, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
			}
		}

	}

?>