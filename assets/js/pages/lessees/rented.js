$('.btn-return').on('click', function() {
	var button = $(this);
	var resId = button.data('rev-id');
	if (confirm('Are you sure to return this item')) {
		$.post(returnUrl, {id: resId}, function(data) {
			if (data['result']) {
				$("tr[data-reservation='"+resId+"']").find('.status').text('return');
				successMessage(data['message']);		
				button.remove();		
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	}
});

$('.reservationUrl').on('click', function() {
	var button = $(this);
	var resId = button.data('rev-id');
	$.post(reservationUrl, {id: resId}, function(data) {
		if (data['result']) {
			$('#reservation-modal').find('.modal-body').html(data['view']);
			$('#reservation-modal').modal('show');
		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');
}); 