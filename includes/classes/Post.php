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
		public function submitPost($body, $user_to, $imageName) {

			// REMOVE ANY HTML TAGS
			$body = strip_tags($body);
			// ESCAPE CHARACTERS THAT MAY CAUSE ISSUES (SINGLE QUOTE)
			$body = mysqli_real_escape_string($this->connection, $body);
			// MAKE POSSIBLE TO POST CAPTION BEFORE VIDEO
			$body = str_replace('\r\n', "\n", $body);
			$body = nl2br($body); 

			// DELETES ALL SPACES
			$check_empty = preg_replace('/\s+/', '', $body); //Deltes all spaces 
	    // CHECK THAT POST HAS CONTENT
			if($check_empty != "") {

				// CREATE ARRAY OUT OF $BODY SPLIT AT SPACES
				$body_array = preg_split("/\s+/", $body);
				// FOREACH LOOP THROUGH ARRAY
				foreach($body_array as $key => $value) {
					// IF $VALUE STRING HAS YOUTUBE LINK IN IT
					if(strpos($value, "www.youtube.com/watch?v=") !== false) {
						// SPLIT LONG LINKS AT AMPERSAND
						$link = preg_split("!&!", $value);
						// REPLACE 'WATCH/' WITH 'EMBED/'
						$value = preg_replace("!watch\?v=!", "embed/", $link[0]);
						// ASSIGN $VALUE IFRAME WITH VIDEO SOURCE
						$value = '<div class="text-center embed-responsive embed-responsive-16by9"><iframe src="'.$value.'""></iframe></div>';
						// ADD NEW $VALUE TO $BODY_ARRAY
						$body_array[$key] = $value;
					}
				}

				// PUT ARRAY BACK INTO $BODY STRING
				$body = implode(" ", $body_array);

				// CURRENT DATE AND TIME
				$date_added = date('Y-m-d H:i:s');
				// GET USERNAME
				$added_by = $this->user_obj->getUsername();

				// IF USER IS ON PROFILE, USER_TO IS 'NONE'
				if ($user_to == $added_by) {
					$user_to = 'none';
				}

				// INSERT POST INTO DATABASE
				$query = mysqli_query($this->connection, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0', '$imageName')");
				// RETURN ID OF POST SUBMITTED
				$return_id = mysqli_insert_id($this->connection);

				// IF USER LOGGED IN IS POSTING ON ANOTHER PROFILE
				// INSERT NOTIFICATION INTO DATABASE
				if ($user_to != 'none') {
					$notification = new Notification($this->connection, $added_by);
					$notification->insertNotification($return_id, $user_to, 'profile_post');
				}

				// GET NUMBER OF POSTS FROM DATABASE
				$num_posts = $this->user_obj->getNumPosts();
				// ADD 1 TO NUMBER OF POSTS 
				$num_posts++;
				// UPDATE NUMBER OF POSTS BACK INTO DATABASE
				$update_query = mysqli_query($this->connection, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");

				$stopWords = "a about above across after again against all almost alone along already
			  also although always among am an and another any anybody anyone anything anywhere are 
			  area areas around as ask asked asking asks at away b back backed backing backs be became
			  because become becomes been before began behind being beings best better between big 
			  both but by c came can cannot case cases certain certainly clear clearly come could
			  d did differ different differently do does done down down downed downing downs during
			  e each early either end ended ending ends enough even evenly ever every everybody
			  everyone everything everywhere f face faces fact facts far felt few find finds first
			  for four from full fully further furthered furthering furthers g gave general generally
			  get gets give given gives go going good goods got great greater greatest group grouped
			  grouping groups h had has have having he her here herself high high high higher
		    highest him himself his how however i im if important in interest interested interesting
			  interests into is it its itself j just k keep keeps kind knew know known knows
			  large largely last later latest least less let lets like likely long longer
			  longest m made make making man many may me member members men might more most
			  mostly mr mrs much must my myself n necessary need needed needing needs never
			  new new newer newest next no nobody non noone not nothing now nowhere number
			  numbers o of off often old older oldest on once one only open opened opening
			  opens or order ordered ordering orders other others our out over p part parted
			  parting parts per perhaps place places point pointed pointing points possible
			  present presented presenting presents problem problems put puts q quite r
			  rather really right right room rooms s said same saw say says second seconds
			  see seem seemed seeming seems sees several shall she should show showed
			  showing shows side sides since small smaller smallest so some somebody
			  someone something somewhere state states still still such sure t take
			  taken than that the their them then there therefore these they thing
			  things think thinks this those though thought thoughts three through
	      thus to today together too took toward turn turned turning turns two
			  u under until up upon us use used uses v very w want wanted wanting
			  wants was way ways we well wells went were what when where whether
			  which while who whole whose why will with within without work
			  worked working works would x y year years yet you young younger
			  youngest your yours z lol haha omg hey ill iframe wonder else like 
        hate sleepy reason for some little yes bye choose";

        // SPLIT $STOP WORDS INTO ARRAY AT SPACES
        $stopWords = preg_split('/[\s,]+/', $stopWords);
        // REPLACE ANYTHING THAT IS NOT A LETTER OR NUMBER WITH NOTHING
        $no_punctuation = preg_replace('/[^a-zA-Z 0-9]+/', '', $body);

        // IF USER IS NOT POSTING A LINK
        if (strpos($no_punctuation, 'height') === false && strpos($no_punctuation, 'width') === false && strpos($no_punctuation, 'http') === false) {
					// SPLIT $NO_PUNCTUATION INTO ARRAY AT SPACES
        	$no_punctuation = preg_split('/[\s,]+/', $no_punctuation);

        	// LOOP THROUGH $STOPWORDS
        	foreach ($stopWords as $value) {
        		// LOOP THROUGH $NO_PUNCTUATION
        		foreach ($no_punctuation as $key => $value2) {
        			// CHECK IF VALUES FROM EACH LOOP MATCH
        			if (strtolower($value) == strtolower($value2)) {
        				$no_punctuation[$key] = '';
        			}
        		}
        	}

        	// CALCULATE TREND WHILE LOOPING THROUGH $NO_PUNCTUATION
        	foreach ($no_punctuation as $value) {
        		$this->calculateTrend(ucfirst($value));
        	}
        }
			}
		}


		// FUNCTION TO CALCULATE TRENDING WORDS
		public function calculateTrend($term) {
			// IF TERM IS NOT BLANK
			if ($term != '') {
				// DATABASE QUERY (GET TRENDS WITH $TERM)
				$query = mysqli_query($this->connection, "SELECT * FROM trends WHERE title='$term'");

				// IF TREND IS NOT ALREADY IN DATABASE, ADD ID
				if (mysqli_num_rows($query) == 0) {
					$insert_query = mysqli_query($this->connection, "INSERT INTO trends VALUES('', '$term', '1')");
					// IF TREND IS ALREADY IN DATABASE, UPDATE USAGE NUMBER
				} else {
					$insert_query = mysqli_query($this->connection, "UPDATE trends SET hits=hits+1 WHERE title='$term'");
				}
			}
		}


		// FUNCTION TO LOAD POSTS FROM FRIENDS (NEWSFEED)
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
					$imagePath = $row['image'];

					// CHECK IF THE POST IS SENT TO A USER
					if ($row['user_to'] == 'none') {
						// IF NOT SET $USER_TO BLANK
						$user_to = '';
					} else {
						// IF SO CREATE USER OBJECT WITH USER_TO FROM DATABASE QUERY
						$user_to_obj = new User($this->connection, $row['user_to']);
						// GET USERNAME OF USER POST IS SENT TO
						$user_to_name = $user_to_obj->getUsername();
						// CREATE USER LINK VARIABLE FOR POST
						$user_to = "to <a href='" . $user_to_name . "'>" . $user_to_name . "</a>";
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
						?>

						<!-- SCRIPT TO HIDE NO POSTS MESSAGE -->
						<script>
							$('#no-posts').css({'display':'none'});
						</script>

						<?php
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

						// IF POST WAS ADDED BY USER LOGGED IN, CREATE DELETE BUTTON
						if ($userLoggedIn == $added_by) {
							$delete_button = "<button class='delete-button btn-danger' id='post$id'>X</button>";
							// ELSE LEAVE DELETE VARIABLE BLANK
						} else {
							$delete_button = '';
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
							// COMMENT TOGGLE FUNCTION
							function toggle<?php echo $id; ?>() {

								var target = $(event.target);
								if (!target.is('a')) {

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

						// IF IMAGE PATH IS NOT BLANK CREATE IMAGE DIV
						if ($imagePath != '') {
							$imageDiv = '<br><div class="postedImage">
													 	<img src="'.$imagePath.'" />
													 </div>';
							// ELSE LEAVE IMAGE DIV BLANK
						} else {
							$imageDiv = '';
						}

						// CREATE POST STRING VARIABLE TO BE DISPLAYED
						$str .= "<div class='status_post'>
											<div class='post_profile_pic'>
												<img src='$profile_pic' width='50' />
											</div>

											<div class='posted_by' style='color: #ACACAC;'>
												<a href='$added_by'> $username </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp; $time_message
													$delete_button
											</div>

											<div id='post_body'>
												$body
												<br />
												$imageDiv
												<br />
												<br />
											</div>

											<div class='newsfeedPostOptions'>
												<span onClick='javascript:toggle$id()'>Comments($comments_check_num)</span>&nbsp;&nbsp;&nbsp;
												<iframe class='like-frame' src='like.php?post_id=$id' scrolling='no'></iframe>
											</div>

										</div>
										<div class='post_comment' id='toggleComment$id' style='display: none;'>
											<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
										</div>
										<hr />";		
					}
					?>

					<script>
						$(document).ready(function() {
							// IF USER CLICKS TO DELETE POST
							$('#post<?php echo $id; ?>').on('click', function() {
								// CONFIRM USER WANTS TO DELETE POST
								bootbox.confirm("Are you sure you want to delete this post?", function(result) {
									// SEND RESULTS TO DELETE_POST.PHP
									$.post('includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>', {result:result});
									// RELOAD PAGE
									if (result) {
										location.reload();
									}
								});
							});
						});
					</script>

					<?php
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


		// FUNCTION TO LOAD POSTS ON USER PROFILE
		public function loadProfilePosts($data, $limit) {
			// CREATE VARIABLES
			$page = $data['page'];
			$profileUser = $data['profileUsername'];
			$userLoggedIn = $this->user_obj->getUsername();

			if ($page == 1) {
				$start = 0;
			} else {
				$start = ($page - 1) * $limit;
			}

			// CREATE STRING VARIABLE
			$str = '';
			// DATABASE QUERY TO GET POSTS BY PROFILE USER OR TO PROFILE USER
			$data_query = mysqli_query($this->connection, "SELECT * FROM posts WHERE deleted='no' AND ((added_by='$profileUser' AND user_to='none') OR user_to='$profileUser') ORDER BY id DESC");

			// IF DATABASE QUERY YEILDS RESULTS
			if (mysqli_num_rows($data_query) > 0) {
				?>

				<!-- SCRIPT TO HIDE NO POSTS MESSAGE -->
				<script>
					$('#no-posts').css({'display':'none'});
				</script>
				
				<?php
				// NUMBER OF RESULTS CHECKED
				$num_iterations = 0;
				$count = 1;

				// LOOP WHILE QUERY YEILDS RESULTS
				while ($row = mysqli_fetch_array($data_query)) {
					// CREATE POST VARIABLES
					$id = $row['id'];
					$body = $row['body'];
					$added_by = $row['added_by'];
					$date_time = $row['date_added'];
					$imagePath = $row['image'];

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

					// IF POST WAS ADDED BY USER LOGGED IN, CREATE DELETE BUTTON
					if ($userLoggedIn == $added_by) {
						$delete_button = "<button class='delete-button btn-danger' id='post$id'>X</button>";
						// ELSE LEAVE DELETE VARIABLE BLANK
					} else {
						$delete_button = '';
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
						// COMMENT TOGGLE FUNCTION
						function toggle<?php echo $id; ?>() {

							var target = $(event.target);
							if (!target.is('a')) {

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

					// IF IMAGE PATH IS NOT BLANK CREATE IMAGE DIV
						if ($imagePath != '') {
							$imageDiv = '<div class="postedImage">
													 	<img src="'.$imagePath.'" />
													 </div>';
							// ELSE LEAVE IMAGE DIV BLANK
						} else {
							$imageDiv = '';
						}

					// CREATE POST STRING VARIABLE TO BE DISPLAYED
					$str .= "<div class='status_post'>
										<div class='post_profile_pic'>
											<img src='$profile_pic' width='50' />
										</div>

										<div class='posted_by' style='color: #ACACAC;'>
											<a href='$added_by'> $username </a> &nbsp;&nbsp;&nbsp;&nbsp; $time_message
												$delete_button
										</div>

										<div id='post_body'>
											$body
											<br />
											$imageDiv
											<br />
											<br />
										</div>

										<div class='newsfeedPostOptions'>
											<span onClick='javascript:toggle$id()'>Comments($comments_check_num)</span>&nbsp;&nbsp;&nbsp;
											<iframe class='like-frame' src='like.php?post_id=$id' scrolling='no'></iframe>
										</div>

									</div>
									<div class='post_comment' id='toggleComment$id' style='display: none;'>
										<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
									</div>
									<hr />";
					?>

					<script>
						$(document).ready(function() {
							// IF USER CLICKS TO DELETE POST
							$('#post<?php echo $id; ?>').on('click', function() {
								// CONFIRM USER WANTS TO DELETE POST
								bootbox.confirm("Are you sure you want to delete this post?", function(result) {
									// SEND RESULTS TO DELETE_POST.PHP
									$.post('includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>', {result:result});
									// RELOAD PAGE
									if (result) {
										location.reload();
									}
								});
							});
						});
					</script>

					<?php
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


		// FUNCTION TO LOAD A SINGLE POST
		public function getSinglePost($post_id) {
			// CREATE $USERLOGGEDIN VARIABLE
			$userLoggedIn = $this->user_obj->getUsername();

			// DATABASE QUERY (SET NOTIFICATION OPENED TO "YES")
			$opened_query = mysqli_query($this->connection, "UPDATE notifications SET opened='yes' WHERE user_to='$userLoggedIn' AND link LIKE '%=$post_id'");

			// CREATE STRING VARIABLE
			$str = '';
			// DATABASE QUERY TO GET POSTS
			$data_query = mysqli_query($this->connection, "SELECT * FROM posts WHERE deleted='no' AND id='$post_id'");

			// IF THE DATABASE QUERY YEILDS RESULTS
			if (mysqli_num_rows($data_query) > 0) {

				// STORE QUERY RESULTS INTO $ROW ARRAY
				$row = mysqli_fetch_array($data_query);

				// CREATE POST VARIABLES
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image'];

				// CHECK IF THE POST IS SENT TO A USER
				if ($row['user_to'] == 'none') {
					// IF NOT SET $USER_TO BLANK
					$user_to = '';
				} else {
					// IF SO CREATE USER OBJECT WITH USER_TO FROM DATABASE QUERY
					$user_to_obj = new User($this->connection, $row['user_to']);
					// GET USERNAME OF USER POST IS SENT TO
					$user_to_name = $user_to_obj->getUsername();
					// CREATE USER LINK VARIABLE FOR POST
					$user_to = "to <a href='" . $user_to_name . "'>" . $user_to_name . "</a>";
				}

				// CREATE NEW USER OBJECT FOR USER WHO ADDED POST
				$added_by_obj = new User($this->connection, $added_by);
				// CHECK IF USER WHO POSTED HAS CLOSED ACCOUNT
				if ($added_by_obj->isClosed()) {
					return;
				}

				// CHECK IF USER WHO POSTED IS FRIENDS WITH LOGGED IN USER
				$user_logged_obj = new User($this->connection, $userLoggedIn);
				if ($user_logged_obj->isFriend($added_by)) {

					// IF POST WAS ADDED BY USER LOGGED IN, CREATE DELETE BUTTON
					if ($userLoggedIn == $added_by) {
						$delete_button = "<button class='delete-button btn-danger' id='post$id'>X</button>";
						// ELSE LEAVE DELETE VARIABLE BLANK
					} else {
						$delete_button = '';
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
						// COMMENT TOGGLE FUNCTION
						function toggle<?php echo $id; ?>() {

							var target = $(event.target);
							if (!target.is('a')) {

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

					// IF IMAGE PATH IS NOT BLANK CREATE IMAGE DIV
					if ($imagePath != '') {
						$imageDiv = '<div class="postedImage">
												 	<img src="'.$imagePath.'" />
												 </div>';
						// ELSE LEAVE IMAGE DIV BLANK
					} else {
						$imageDiv = '';
					}

					// CREATE POST STRING VARIABLE TO BE DISPLAYED
					$str .= "<div class='status_post'>
										<div class='post_profile_pic'>
											<img src='$profile_pic' width='50' />
										</div>

										<div class='posted_by' style='color: #ACACAC;'>
											<a href='$added_by'> $username </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp; $time_message
												$delete_button
										</div>

										<div id='post_body'>
											$body
											<br />
											$imageDiv
											<br />
											<br />
										</div>

										<div class='newsfeedPostOptions'>
											<span onClick='javascript:toggle$id()'>Comments($comments_check_num)</span>&nbsp;&nbsp;&nbsp;
											<iframe class='like-frame' src='like.php?post_id=$id' scrolling='no'></iframe>
										</div>

									</div>
									<div class='post_comment' id='toggleComment$id' style='display: none;'>
										<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
									</div>
									<hr />";
				?>

				<script>
					$(document).ready(function() {
						// IF USER CLICKS TO DELETE POST
						$('#post<?php echo $id; ?>').on('click', function() {
							// CONFIRM USER WANTS TO DELETE POST
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {
								// SEND RESULTS TO DELETE_POST.PHP
								$.post('includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>', {result:result});
								// RELOAD PAGE
								if (result) {
									location.reload();
								}
							});
						});
					});
				</script>

				<?php
					// IF USER WHO POSTED IS NOT FRIENDS WITH LOGGED IN USER
				} else {
					echo '<p>You Cannot See this Post Becuase You Are Not Friends with this User.</p>';
					return;
				}
				// IF DATABASE QUERY YEILDS NO RESULTS
			} else {
				echo '<p>No Post Found. If You Clicked a Link it May be Broken.</p>';
				return;
			}
			// DISPLAY POST VARIABLE ON PAGE
			echo $str;
		}
	}

?>