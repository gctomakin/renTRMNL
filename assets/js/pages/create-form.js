$(document).ready(function() {
	$('#create-form').submit(function(e) {
		e.preventDefault();
		var form = $(this);
		
		// Prevent from spamming of Request
		if (form.data('requestRunning') ) { // Check if request is Running
    	return;
    }
    
    form.data('requestRunning', true);

		var action = form.attr('action');
		$.post(action, form.serialize(), function(data) {
			if (data['result']) {
				successMessage(data['message']);
				form.find('input:first').focus();
				if (typeof data['reset'] != 'undefined') { $('#form-reset-btn').click(); }
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON').always(function() {
    	form.data('requestRunning', false);
    });
	});
	$('#create-form #name').focus();
}); 