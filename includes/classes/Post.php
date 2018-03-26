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

		// FUNCTION TO LOAD POSTS FROM FRIENDS
		public function loadPostsFriends() {
			// CREATE STRING VARIABLE
			$str = '';
			// DATABASE QUERY TO GET POSTS
			$data = mysqli_query($this->connection, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

			// LOOP THROUGH QUERY RESULTS ARRAY
			while ($row = mysqli_fetch_array($data)) {
				// CREATE POST VARIABLES
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];

				// CHECK IF THE POST IS SENT TO A USER
				if ($row['user_to'] == 'none') {
					// IF NOT SET $USER_TO BLANK
					$user_to = '';
					
				} else {
					// IF SO CREATE USER CLASS WITH USER_TO FROM DATABASE QUERY
					$user_to_obj = new User($connection, $row['user_to']);
					// GET FIRST AND LAST NAME OF USER
					$user_to_name = $user_to_obj->getFirstAndLastName();
					// CREATE USER LINK VARIABLE FOR POST
					$user_to = "<a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
				}

			}


		}

	}

?>