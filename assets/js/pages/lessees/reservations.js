$(document).ready(function() {
	$('#reservation-table').DataTable({});
}); 

$('.btn-cancel').on('click', function() {
	var button = $(this);
	var id = button.data('rev-id');
	if (confirm('Are you sure to cancel this reservation?')) {
		$.post(reservationCancelUrl, {id:id}, function(data) {
			if (data['result']) {
				button.hide();
				$('[data-reservation="'+id+'"]').find('.status').text('cancel');
				successMessage(data['message']);
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	}
});

$('.btn-view').on('click', function() {
	var id = $(this).data('rev-id');
	$.post(reservationDetailUrl, {id:id}, function(data) {
		if (data['result']) {
			$('#reservation-modal').find('.modal-body').html(data['view']);
			$('#reservation-modal').modal('show');
		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');
});

$('.btn-rent').on('click', function() {
	var id = $(this).data('rev-id');
	successMessage('Please wait while connecting to paypal');
	$.post(rentUrl, {id:id}, function(data) {
		if (data['result']) {
			if (data['paypal']['result']) {
				$('#paykey').val(data['paypal']['packet']['payKey']);
				$('#btn-pay').click();
			} else {
				errorMessage(data['paypal']['message']);
			}
		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');
});

function closePaypal()  {
	dgFlow.closeFlow();
	top.close();
	window.reload
}