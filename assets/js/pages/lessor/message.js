$(document).ready(function() {
	// var detailTemplate = _.template($('#message-detail-template').html());

	var body = $('#body-convo');
	toDown();
	$('#message-form').submit(function(e) {
		e.preventDefault();
		var text = $('#text-convo');
		if (text.val() != '') {
			/* 'image':'http://placehold.it/150x90' */
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
				toType: 'lessee',
				fromType: 'lessor',
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
		var currentReceiver = $('#receiver :selected');
		console.log(currentReceiver.text());
		$('#name-convo').text(currentReceiver.text());
		$('#btn-convo').prop('disabled', false);
		$('#text-convo').prop('disabled', false);
		$('#body-convo').html('');
		$('#text-convo').text('');
		setTimeout(function() {
			$('#text-convo').focus();
		}, 200);

		var name = currentReceiver.text().split(',');
		$.post(messageConverstationUrl, {
			lesseeId: currentReceiver.val(),
			lessorId: session_id,
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

	$('#receiver').select2({'placeholder': 'Choose a Lessor'});

	// var message = $('#message').val();
	// if (message != '') {
	// 	// console.log(mesage);
	// 	var body = $('#body-convo');
	// 	body.append(detailTemplate({
	// 		name: $('#receiver :selected').text(),
	// 		message: _.escape(message),
	// 		position: 'pull-left',
	// 		date: moment().format('MM/DD/YYYY HH:mm:SS'),
	// 		image : 'http://placehold.it/140x100'
	// 	}));
	// 	toDown();
	// }

	function toDown() {
		body.animate({ scrollTop: body[0].scrollHeight - body.height() }, "slow");
	}
}); 
