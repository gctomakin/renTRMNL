$(document).ready(function() {
	$('#account-form').submit(function(e) {
		e.preventDefault();
		var url = $(this).attr('action');
		proccessAction(url, $(this).serialize());
	});
}); 