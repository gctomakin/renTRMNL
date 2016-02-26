$(document).ready(function() {
	$('#profile-form').submit(function(e) {
		e.preventDefault();
		var action = $(this).attr('action');
		proccessAction(action, $(this).serialize());
	});
}); 