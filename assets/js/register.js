$(document).ready(function() {

	// SHOW SIGN UP FORM (AND HIDE LOGIN FORM) WHEN USER CLICKS SIGN UP LINK
	$('#signup').click(function() {
		$('#first').slideUp('slow' ,function() {
			$('#second').slideDown('slow');
		});
	});

	// SHOW LOGIN FORM (AND HIDE SIGN UP FORM) WHEN USER CLICKS SIGN IN LINK
	$('#signin').click(function() {
		$('#second').slideUp('slow' ,function() {
			$('#first').slideDown('slow');
		});
	});

});