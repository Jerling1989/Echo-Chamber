<?php

	class Post {
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
		public function loadPostsFriends($data, $limit) {

			$page = $data['page'];
			$userLoggedIn = $this->user_obj->getUsername();

			if ($page == 1) {
				$start = 0;
			} else {
				$start = ($page - 1) * $limit;
			}

			// CREATE STRING VARIABLE
			$str = '';
			// DATABASE QUERY TO GET POSTS
			$data_query = mysqli_query($this->connection, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

			if (mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; // NUMBER OF RESULTS CHECKED
				$count = 1;

				// LOOP THROUGH QUERY RESULTS ARRAY
				while ($row = mysqli_fetch_array($data_query)) {
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
						// IF SO CREATE USER OBJECT WITH USER_TO FROM DATABASE QUERY
						$user_to_obj = new User($connection, $row['user_to']);
						// GET USERNAME OF USER POST IS SENT TO
						$user_to_name = $user_to_obj->getUsername();
						// CREATE USER LINK VARIABLE FOR POST
						$user_to = "to <a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
					}

					// CREATE NEW USER OBJECT FOR USER WHO ADDED POST
					$added_by_obj = new User($this->connection, $added_by);
					// CHECK IF USER WHO POSTED HAS CLOSED ACCOUNT
					if ($added_by_obj->isClosed()) {
						continue;
					}

					// CHECK IF USER WHO POSTED IS FRIENDS WITH LOGGED IN USER
					$user_logged_obj = new User($this->connection, $userLoggedIn);
					if ($user_logged_obj->isFriend($added_by)) {


						// CHECK IF IT'S GONE THROUGH ALL POSTS THAT HAVE BEEN LOADED
						if ($num_iterations++ < $start) {
							continue;
						}
						// ONCE 10 POSTS HAVE BEEN LOADED, BREAK
						if ($count > $limit) {
							break;
						} else {
							$count++;
						}

						// DATABASE QUERY TO GET FIRST NAME, LAST NAME, & PROFILE PIC OF USER WHO ADDED POST
						$user_details_query = mysqli_query($this->connection, "SELECT username, profile_pic FROM users WHERE username='$added_by'");
						// STORE QUERY RESULTS INTO ARRAY
						$user_row = mysqli_fetch_array($user_details_query);
						// CREATE USERNAME VARIABLE FROM QUERY
						$username = $user_row['username'];
						// CREATE PROFILE_PIC VARIABLE FROM QUERY
						$profile_pic = $user_row['profile_pic'];

						?>

						<script>
							// CREATE TOGGLE FUNCTION
							function toggle<?php echo $id; ?>() {
								// GET COMMENT BY ID, STORE IN VARIABLE
								var element = document.getElementById('toggleComment<?php echo $id; ?>');

								// IF SECTION IS VISIBLE, THEN HIDE IT
								if (element.style.display == 'block') {
									element.style.display = 'none';
									// IF SECTION IS HIDDEN, MAKE IT VISIBLE
								} else {
									element.style.display = 'block';
								}
							}
						</script>

						<?php

						// DATABASE QUERY TO CHECK FOR COMMENTS
						$comment_check = mysqli_query($this->connection, "SELECT * FROM comments WHERE post_id='$id'");
						// STORE NUMBER OF RESULTS FROM QUERY
						$comments_check_num = mysqli_num_rows($comment_check);

						// CURRENT TIME
						$date_time_now = date('Y-m-d H:i:s');
						// DATE POST WAS ADDED VARIABLE
						$start_date = new DateTime($date_time);
						// CURRENT DATE VARIABLE
						$end_date = new DateTime($date_time_now);
						// DIFFERENCE BETWEEN BOTH DATE VARIABLES
						$interval = $start_date->diff($end_date);
						// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE YEAR
						if ($interval->y >= 1) {
							if($interval == 1) {
								$time_message = $interval->y . ' year ago'; // ONE YEAR
							} else {
								$time_message = $interval->y . ' years ago'; // MULTIPLE YEARS
							}
							// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE MONTH
						} else if ($interval->m >= 1) {
							if ($interval->d == 0) {
								$days = ' ago'; // NO ADDITIONAL DAYS
							} else if ($interval->d == 1) {
								$days = $interval->d . ' day ago'; // ONE ADDITIONAL DAYS
							} else {
								$days = $interval->d . ' days ago'; // MULTIPLE ADDITIONAL DAYS
							}

							if($interval->m == 1) {
								$time_message = $interval->m . ' month ago' . $days; // ONE MONTH PLUS DAYS
							} else {
								$time_message = $interval->m . ' months ago' . $days; // MULTIPLE MONTHS PLUS DAYS
							}
							// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE DAY
						} else if ($interval->d >= 1) {
							if ($interval->d == 1) {
								$time_message = 'Yesterday';
							} else {
								$time_message = $interval->d . ' days ago';
							}
							// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE HOUR
						} else if ($interval->h >= 1) {
							if ($interval->h == 1) {
								$time_message = $interval->h . ' hour ago'; // ONE HOUR
							} else {
								$time_message = $interval->h . ' hours ago'; // MULTIPLE HOURS
							}
							// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE MINUTE
						} else if ($interval->i >= 1) {
							if ($interval->i == 1) {
								$time_message = $interval->i . ' minute ago'; // ONE MINUTE
							} else {
								$time_message = $interval->i . ' minutes ago'; // MULTIPLE MINUTES
							}
							// CHECK IF DIFFERENCE IS GREATER THAN OR EQUAL TO ONE SECOND
						} else {
							if ($interval->s < 30) {
								$time_message = 'Just now'; // LESS THAN 30 SECONDS
							} else {
								$time_message = $interval->s . ' seconds ago'; // OVER 30 SECONDS
							}
						}

						// CREATE POST STRING VARIABLE TO BE DISPLAYED
						$str .= "<div class='status_post' onClick='javascript:toggle$id()'>
											<div class='post_profile_pic'>
												<img src='$profile_pic' width='50' />
											</div>

											<div class='posted_by' style='color: #ACACAC;'>
												<a href='$added_by'> $username </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp; $time_message
											</div>

											<div id='post_body'>
												$body
												<br />
												<br />
												<br />
											</div>

											<div class='newsfeedPostOptions'>
												Comments($comments_check_num)&nbsp;&nbsp;&nbsp;

											</div>

										</div>
										<div class='post_comment' id='toggleComment$id' style='display: none;'>
											<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
										</div>
										<hr />";		
					}

				} // END WHILE LOOP


				// IF THERE ARE MORE POSTS, ENABLE MORE SCROLLING
				if ($count > $limit) {
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
									 <input type='hidden' class='noMorePosts' value='false'>";
					// ELSE DISPLAY 'NO MORE POSTS TO SHOW'
				} else {
					$str .= "<input type='hidden' class='noMorePosts' value='true'>
									 <p style='text-align: center'>No More Posts to Show</p>";
				}

			}
			// DISPLAY POST VARIABLE ON PAGE
			echo $str;


		}

	}

?>