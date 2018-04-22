<?php
	// INCLUDE NECCESSARY FILES AND SCRIPTS
	include('includes/header.php');
?>

<!-- MOBILE MESSAGES PANEL -->
<div class="column col-xl-10 col-lg-12 col-md-12 text-center" id="mobile-messages-panel">
	<!-- WINDOW TO LOAD MESSAGE DATA -->
	<div id="mobile-message-data"></div>
	<br />
	<!-- LINK TO START NEW CONVERSATION -->
	<a href="messages.php?u=new"><h5>New Message</h5></a>
</div>
<!-- END MOBILE MESSAGES PANEL -->

</div>
<!-- END WRAPPER DIV -->

<!-- SCRIPT TO LOAD MESSAGES -->
<script>
	window.onload = function() {
	  getMobileMessages('<?php echo $userLoggedIn; ?>');
	};

	// DOCUMENT READY FUNCTION
	$(document).ready(function() {

		// AUTO LOAD MESSAGES (INFINITE SCROLLING) FUNCTION
		$('#mobile-message-data').scroll(function() {
			// DROPDOWN_DATA_WINDOW DIV HEIGHT VARIABLE
			var inner_height = $('#mobile-message-data').innerHeight();
			// SCROLLTOP VARIABLE
			var scroll_top = $('#mobile-message-data').scrollTop();
			// VARIABLE FOR NEXT PAGE (MORE POSTS)
			var page = $('#mobile-message-data').find('.nextPageDropdownData').val();
			// VARIABLE FOR NO MORE POSTS
			var noMoreData = $('#mobile-message-data').find('.noMoreDropdownData').val();

			// CHECK IF THE PAGE IS SCROLLED TO THE BOTTOM OF MOBILE-MESSAGE-DATA DIV
			// AND THERE ARE ALSO MORE POSTS
			if ((scroll_top + inner_height >= $('#mobile-message-data')[0].scrollHeight) && noMoreData == 'false') {

				// VARIABLE OF AJAX REQUEST FOR MORE MESSAGES
				var ajaxReq = $.ajax({
					url: 'includes/handlers/ajax_load_messages.php',
					type: 'POST',
					data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
					cache:false,

					success: function(response) {
						// REMOVE CURRENT .NEXTPAGE
						$('#mobile-message-data').find('.nextPageDropdownData').remove();
						// REMOVE CURRENT NOMORE POSTS
						$('#mobile-message-data').find('.noMoreDropdownData').remove();

						// LOAD POSTS ONTO POSTS_AREA DIV
						$('#mobile-message-data').append(response);
					}
				});
			} // END IF
			return false;
		}); // END AUTO LOAD POSTS FUNCTION
	});
</script>
<!-- SCRIPT TO LOAD MESSAGES -->

</body>
</html>