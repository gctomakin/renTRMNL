$(document).ready(function() {
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
}); 