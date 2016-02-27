$(document).ready(function() {
	var detailTemplate = _.template($('#message-detail-template').html());

	$('#message-form').submit(function(e) {
		e.preventDefault();
		var text = $('#text-convo');
		if (text.val() != '') {
			var body = $('#body-convo');
			body.append(detailTemplate({
				name: 'Me',
				message: _.escape(text.val()),
				position: 'pull-right',
				date: moment().format('MM/DD/YYYY HH:mm:SS')
			}));
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
		$('#body-convo').html('');
		$('#text-convo').text('');
		setTimeout(function() {
			$('#text-convo').focus();
		}, 200);
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