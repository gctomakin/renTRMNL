$(document).ready(function() {
	$('#picture').change(function() {
		previewImage(this);
	});

	$('#category').select2({
		'placeholder': 'Choose a category',
		'tags': true,
		ajax: {
			url: categoryUrl,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
	        q: params.term, // search term
	        page: params.page
	      };
			},
			processResults: function (data) {
	    	return { results: data.items };
	    }	
		}
	});

	$('#shop').select2({
		'placeholder': 'Choose a shop',
		ajax: {
			url: shopUrl,
	    dataType: 'json',
	    delay: 250,
	    data: function (params) {
	      return {
	        q: params.term, // search term
	        page: params.page
	      };
	    },
	    processResults: function (data) {
	    	return { results: data.items };
	    }
		}
	});

	$('#rentalmode').select2();

	$('#item-form').submit(function(e) {
		e.preventDefault();
		var form = $(this);
		if (form.data('requestRunning')) { return; }
		form.data('requestRunning', true);
		var fData = new FormData(form[0]);
		$.ajax({
		    url: form.attr('action'),
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
	});
});