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
		var fData = new FormData(form[0]);
		$.ajax({
		    url: action,
		    type: 'POST',
		    contentType: false,                    
		    cache: false,               
		    dataType: 'json',
		    data: fData,
		    processData: false,
		    success: function(data) {
		    	if (data['result']) {
						successMessage(data['message']);
						if (typeof data['reset'] != 'undefined') {
							$('#form-reset-btn').click();
							$('#preview-image').attr('src', '');
						}
					} else {
						errorMessage(data['message']);
					}
		    },
		    complete: function() {
		    	form.data('requestRunning', false);
		    }
		});
		// $.post(action, form.serialize(), function(data) {
		// 	if (data['result']) {
		// 		successMessage(data['message']);
		// 		form.find('input:first').focus();
		// 		if (typeof data['reset'] != 'undefined') { $('#form-reset-btn').click(); }
		// 	} else {
		// 		errorMessage(data['message']);
		// 	}
		// }, 'JSON').always(function() {
  //   	form.data('requestRunning', false);
  //   });
	});
	$('#create-form #name').focus();
}); 