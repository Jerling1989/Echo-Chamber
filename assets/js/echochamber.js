$(document).ready(function() {

	// WHEN POST BUTTON IS CLICKED ON PROFILE POST MODAL
	$('#submit_profile_post').click(function() {
		// AJAX REQUEST FOR SENDING POST
		$.ajax({
			type: 'POST',
			url: 'includes/handlers/ajax_submit_profile_post.php',
			data: $('form.profile_post').serialize(),
			success: function(msg) {
				$('post_form').modal('hide');
				location.reload();
			},
			error: function() {
				alert('Failure');
			}
		});
	});

});


// FUNCTION TO GET USERS FROM SEARCH FOR NEW USER TO MESSAGE
function getUsers(value, user) {
	// POST AJAX WITH VALUES
	$.post('includes/handlers/ajax_friend_search.php', {query:value, userLoggedIn:user}, function(data) {
		// LOAD DATA IN RESULTS DIV
		$('.results').html(data);
	});


// FUNCTION TO LOAD DATA FOR DROPDOWN MENU ITEMS
function getDropdownData(user, type) {

	// IF DROPDOWN ITEM IS CLOSED, OPEN ON CLICK (NAVBAR MESSAGES ICON)
	if ($('.dropdown_data_window').css('height') == '0px') {
		// DECLARE PAGENAME VARIABLE
		var pageName;

		// IF TYPE IS NOTIFICATION
		if (type == 'notification') {


			// IF TYPES IS MESSAGE
		} else if (type == 'message') {
			// ASSIGN AJAX_LOAD_MESSAGES.PHP TO PAGENAME VARIABLE
			pageName = 'ajax_load_messages.php';
			// REMOVE #UNREAD_MESSAGE DIV
			$('span').remove('#unread_message');
		}

		// AJAX CALL TO RETRIEVE DATA
		var ajaxreq = $.ajax({
			url: 'includes/handlers/' + pageName,
			type: 'POST',
			data: 'page=1&user=' + user,
			cache: false,
			// IF AJAX CALL IS SUCCESSFUL
			success: function(response) {
				// PUT AJAX RESPONSE DATA INTO CORRECT DROPDOWN MENU
				$('.dropdown_data_window').html(response);
				$('.dropdown_data_window').css({'padding':'0px', 'height':'280px'});
				$('#dropdown_data_type').val(type);
			}
		});

		// IF DROPDOWN IS OPEN, CLOSE ON CLICK (NAVBAR MESSAGES ICON)
	} else {
		$('.dropdown_data_window').html('');
		$('.dropdown_data_window').css({'padding':'0px', 'height':'0px'});
	}

}















