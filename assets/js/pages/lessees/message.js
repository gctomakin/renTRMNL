$(document).ready(function() {
	var body = $('#body-convo');
	toDown();
	$('#message-form').submit(function(e) {
		e.preventDefault();
		var text = $('#text-convo');
		if (text.val() != '') {
			body.append(detailTemplate({
				name: 'Me',
				message: _.escape(text.val()),
				position: 'pull-right',
				date: moment().format('MM/DD/YYYY HH:mm:SS')
			}));
			toDown();
			$.post(messageSendUrl, {
				to: $('#receiver :selected').val(),
				from: session_id,
				toType: 'lessor',
				fromType: 'lessee',
				name: $('#receiver :selected').text(),
				message: text.val()
			}, function(data) {
				if (data['result']) {
					console.log(data);		
				} else {
					errorMessage(data['message']);
				}
			}, 'JSON');
			text.val('');
		}
		text.focus();
	});

	$('#receiver').change(function() {
		// console.log($('#receiver :selected').text());
		$('#name-convo').text($('#receiver :selected').text());
		$('#btn-convo').prop('disabled', false);
		$('#text-convo').prop('disabled', false);
		$('#body-convo').html('');
		$('#text-convo').text('');
		setTimeout(function() {
			$('#text-convo').focus();
		}, 200);
		var name = $('#receiver :selected').text().split(',');		
		$.post(messageConverstationUrl, {
			lessorId: $('#receiver :selected').val(),
			lesseeId: session_id,
			name: name[1]
		}, function(data) {
			if (data['result']) {
				body.html(data['view']);
				toDown();	
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
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

	function toDown() {
		body.animate({ scrollTop: body[0].scrollHeight - body.height() }, "slow");
	}
}); 