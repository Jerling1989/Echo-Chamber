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