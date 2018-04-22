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


		// FUNCTION TO GET NOTIFICATIONS FOR DROPDOWN MENU
		public function getNotifications($data, $limit) {
			// SET $PAGE VARIABLE
			$page = $data['page'];
			// SET $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();
			// CREATE EMPTY $RETURN_STRING VARIABLE
			$return_string = '';

			// IF STATEMENT TO TELL WHEN TO LOAD POSTS
			if ($page == 1) {
				$start = 0;
			} else {
				$start = ($page - 1) * $limit;
			}

			// DATABASE QUERY (CHANGE VIEWED TO "YES" IN NOTIFICATIONS TABLE)
			$set_viewed_query = mysqli_query($this->connection, "UPDATE notifications SET viewed='yes' WHERE user_to='$userLoggedIn'");

			// DATABASE QUERY (SELECT ALL IN NOTIFICATIONS FOR LOGGED IN USER)
			$query = mysqli_query($this->connection, "SELECT * FROM notifications WHERE user_to='$userLoggedIn' ORDER BY id DESC");

			// IF THERE ARE NO RESULTS FROM QUERY
			if (mysqli_num_rows($query) == 0) {
				echo 'You have no notifications!';
				return;
			}

			$num_iterations = 0; // NUMBER OF MESSAGES CHECKED
			$count = 1; // NUMBER OF MESSAGES POSTED

			// LOOP WHILE QUERY YEILDS RESULTS
			while ($row = mysqli_fetch_array($query)) {

				// IF IT HASN'T REACHED START POINT CONTINUE
				if ($num_iterations++ < $start) {
					continue;
				}

				// IF NUMBER OF MESSAGES EXCEEDS LIMIT, BREAK LOOP
				if ($count > $limit) {
					break;
				} else {
					$count++;
				}

				// CREATE $USER_FROM VARIABLE
				$user_from = $row['user_from'];
				// DATABASE QUERY (SELECT ALL DATA FOR USER WHO NOTIFICATION IS FROM)
				$user_data_query = mysqli_query($this->connection, "SELECT * FROM users WHERE username='$user_from'");
				// STORE QUERY RESULTS IN $USER_DATA ARRAY
				$user_data = mysqli_fetch_array($user_data_query);

				// CURRENT TIME
				$date_time_now = date('Y-m-d H:i:s');
				// DATE POST WAS ADDED VARIABLE
				$start_date = new DateTime($row['datetime']);
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

				// VARIABLE FOR WHETHER NOTIFICATION HAS BEEN OPENED OR NOT
				$opened = $row['opened'];
				// CONDITIONAL TO STORE DIFFERENT BACKGROUND-COLOR IN $STYLE VARIABLE
				// TO DISTINGUISH BETWEEN OPENED AND UNOPENED NOTIFICATIONS
				$style = ($row['opened'] == 'no') ? "background-color: #DDEDFF;" : "";

				// ADD MESSAGE DETAILS TO $RETURN_STRING VARIABLE
				$return_string .= '<a href="'.$row['link'].'">
														<div class="resultDisplay resultDisplayNotification" style="'.$style.'">
														 	<div class="notificationsProfilePic">
														 		<img src="'.$user_data['profile_pic'].'" />
														 		<p class="timestamp_smaller grey-font">
														 		'.$time_message.'
														 		</p><br />'.$row['message'].'
														 	</div>
													 	</div>
													 </a>';
			}

			// IF POSTS WERE LOADED
			if ($count > $limit) {
				$return_string .= "<input type='hidden' class='nextPageDropdownData' value='".($page+1)."' />
													 <input type='hidden' class='noMoreDropdownData' value='false' />";
			} else {
				$return_string .= "<input type='hidden' class='noMoreDropdownData' value='true' />
													 <p style='text-align: center; margin-top: 5px; color: #8C8C8C;'>No More Notifications to Load</p>";
			}

			// RETURN $RETURN_STRING
			return $return_string;
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