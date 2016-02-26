$(document).ready(function() {
	var detailTemplate = _.template($('#message-detail-template').html());

	$('#message-form').submit(function(e) {
		e.preventDefault();
		var text = $('#text-convo');
		if (text.val() != '') {
			var body = $('#body-convo');
			body.append(detailTemplate({name: 'Me', message: text.val(), 'position': 'pull-right'}));
			body.animate({ scrollTop: body[0].scrollHeight - body.height() }, "slow");
			text.val('');
		}
		text.focus();
	});

	$('#receiver').change(function() {
		console.log($('#receiver :selected').text());
		$('#name-convo').text($('#receiver :selected').text());
		$('#btn-convo').prop('disabled', false);
		$('#text-convo').prop('disabled', false);
		$('#text-convo').text('');
	});

	$('#receiver').select2({
		'placeholder': 'Choose a Lessor',
		ajax: {
			url: lessorListUrl,
	    dataType: 'json',
	    delay: 250,
	    data: function (params) {
	      return {
	        q: params.term, // search term
	        page: params.page
	      };
	    },
	    processResults: function (data) {
	    	console.log(data);
	    	return { results: data };
	    }
		}
	});
}); 