$(document).ready(function() {
	var detailTemplate = _.template($('#message-detail-template').html());

	$('#message-form').submit(function(e) {
		e.preventDefault();
		var text = $('#text-convo');
		if (text.val() != '') {
			var body = $('#body-convo');
			/* 'image':'http://placehold.it/50x50' */
			body.append(detailTemplate({name: 'Me', message: _.escape(text.val()), 'position': 'pull-right'}));
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

	$('#receiver').select2({'placeholder': 'Choose a Lessor'});
}); 