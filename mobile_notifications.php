<?php
	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');
?>

<!-- MOBILE NOTIFICATIONS PANEL -->
<div class="column col-xl-10 col-lg-12 col-md-12 text-center" id="mobile-notifications-panel">
	<!-- WINDOW TO LOAD NOTIFICATION DATA -->
	<div id="mobile-notification-data"></div>
</div>
<!-- END MOBILE NOTIFICATIONS PANEL -->

</div>
<!-- END WRAPPER DIV -->

<!-- SCRIPT TO LOAD NOTIFICATIONS -->
<script>
	// RUN FUNCTION WHEN PAGE LOADS
	window.onload = function() {
	  getMobileNotifications('<?php echo $userLoggedIn; ?>');
	};

	// CREATE USERLOGGEDIN VARIABLE
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';

	// DOCUMENT READY FUNCTION
	$(document).ready(function() {

		// AUTO LOAD NOTIFICATIONS (INFINITE SCROLLING) FUNCTION
		$('#mobile-notification-data').scroll(function() {
			// DROPDOWN_DATA_WINDOW DIV HEIGHT VARIABLE
			var inner_height = $('#mobile-notification-data').innerHeight();
			// SCROLLTOP VARIABLE
			var scroll_top = $('#mobile-notification-data').scrollTop();
			// VARIABLE FOR NEXT PAGE (MORE POSTS)
			var page = $('#mobile-notification-data').find('.nextPageDropdownData').val();
			// VARIABLE FOR NO MORE POSTS
			var noMoreData = $('#mobile-notification-data').find('.noMoreDropdownData').val();

			// CHECK IF THE PAGE IS SCROLLED TO THE BOTTOM OF MOBILE-NOTIFICATIONS-DATA DIV
			// AND THERE ARE ALSO MORE POSTS
			if ((scroll_top + inner_height >= $('#mobile-notification-data')[0].scrollHeight) && noMoreData == 'false') {

				// VARIABLE OF AJAX REQUEST FOR MORE NOTIFICATIONS
				var ajaxReq = $.ajax({
					url: 'includes/handlers/ajax_load_notifications.php',
					type: 'POST',
					data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
					cache:false,

					success: function(response) {
						// REMOVE CURRENT .NEXTPAGE
						$('#mobile-notification-data').find('.nextPageDropdownData').remove();
						// REMOVE CURRENT NOMORE POSTS
						$('#mobile-notification-data').find('.noMoreDropdownData').remove();

						// LOAD POSTS ONTO POSTS_AREA DIV
						$('#mobile-notification-data').append(response);
					}
				});
			} // END IF
			return false;
		}); // END AUTO LOAD POSTS FUNCTION
	});
</script>
<!-- END SCRIPT TO LOAD NOTIFICATIONS -->

</body>
</html>