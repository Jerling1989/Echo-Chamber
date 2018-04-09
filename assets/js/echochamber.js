$(document).ready(function() {

	// EXPAND SEARCH FORM WHEN CLICKED ON
	$('#search_text_input').focus(function() {
		// IF WINDOW IS WIDER THAN 800PX
		if (window.matchMedia('(min-width: 800px)').matches) {
			// ANIMATE SEARCH FORM TO WIDEN
			$(this).animate({width: '250px'}, 500);
		}
	});


	// SUBMIT SEARCH FORM WHEN MAGNIFYING GLASS ICON IS PRESSED
	$('.button_holder').on('click', function() {
		document.search_form.submit();
	});


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


// CLOSE DROPDOWN WINDOWS WHEN USER CLICKS AWAY
$(document).click(function(e) {

	// IF USER CLICKS AWAY FROM SEARCH RESULTS
	if (e.target.class != 'search_results' && e.target.id != 'search_text_input') {
		$('.search_results').html('');
		$('.search_results_footer').html('');
		$('.search_results_footer').toggleClass('search_results_footer_empty');
		$('.search_results_footer').toggleClass('search_results_footer');
	}

	// IF USER CLICKS AWAY FROM MESSAGE/NOTIFICATION DROPDOWN WINDOW
	if (e.target.class != 'dropdown_data_window') {
		$('.dropdown_data_window').html('');
		$('.dropdown_data_window').css({'padding':'0px', 'height':'0px'});
	}

});


// FUNCTION TO GET USERS FROM SEARCH FOR NEW USER TO MESSAGE
function getUsers(value, user) {
	// POST AJAX WITH VALUES
	$.post('includes/handlers/ajax_friend_search.php', {query:value, userLoggedIn:user}, function(data) {
		// LOAD DATA IN RESULTS DIV
		$('.results').html(data);
	});
}


// FUNCTION TO LOAD DATA FOR DROPDOWN MENU ITEMS
function getDropdownData(user, type) {

	// IF DROPDOWN ITEM IS CLOSED, OPEN ON CLICK (NAVBAR MESSAGES ICON)
	if ($('.dropdown_data_window').css('height') == '0px') {
		// DECLARE PAGENAME VARIABLE
		var pageName;

		// IF TYPE IS NOTIFICATION
		if (type == 'notification') {
			// ASSIGN AJAX_LOAD_NOTIFICATIONS.PHP TO PAGENAME VARIABLE
			pageName = 'ajax_load_notifications.php';
			// REMOVE #UNREAD_NOTIFICATION DIV
			$('span').remove('#unread_notification');


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
			data: 'page=1&userLoggedIn=' + user,
			cache: false,
			// IF AJAX CALL IS SUCCESSFUL
			success: function(response) {
				// PUT AJAX RESPONSE DATA INTO CORRECT DROPDOWN MENU
				$('.dropdown_data_window').html(response);
				$('.dropdown_data_window').css({'padding':'0px', 'height':'280px', 'border' : '1px solid #DADADA'});
				$('#dropdown_data_type').val(type);
			}
		});

	// IF DROPDOWN IS OPEN, CLOSE ON CLICK (NAVBAR MESSAGES ICON)
	} else {
		$('.dropdown_data_window').html('');
		$('.dropdown_data_window').css({'padding':'0px', 'height':'0px', 'border' : 'none'});
	}
}


// FUNCTION TO LOAD DATA FROM SEARCH FORM
function getLiveSearchUsers(value, user) {
	// AJAX CALL
	$.post('includes/handlers/ajax_search.php', {query:value, userLoggedIn:user}, function(data) {

		// TOGGLE CLASS
		if ($('.search_results_footer_empty')[0]) {
			$('.search_results_footer_empty').toggleClass('search_results_footer');
			$('.search_results_footer_empty').toggleClass('search_results_footer_empty');
		}

		// PUT DATA FROM AJAX CALL INTO SEARCH RESULTS LIVE PANEL
		// WITH LINK TO RESULTS PAGE ON FOOTER
		$('.search_results').html(data);
		$('.search_results_footer').html('<a href="search.php?q='+value+'">See All Results</a>');

		// IF THE USER SEARCHES NOTHING
		if (data == '') {
			$('.search_results_footer').html('');
			$('.search_results_footer').toggleClass('search_results_footer_empty');
			$('.search_results_footer').toggleClass('search_results_footer');
		}
	});
}













