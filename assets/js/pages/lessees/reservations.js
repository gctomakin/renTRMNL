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
				$('[data-reservation="'+id+'"]').find('.status').text(data['status']);
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

var resId = 0;

$('.btn-rent').on('click', function() {
	resId = $(this).data('rev-id');
	var balance = $('[data-reservation="'+resId+'"]').find('.total-balance').html();
	var amount = $('[data-reservation="'+resId+'"]').find('.total-amount').html();
	if (parseFloat(balance) < parseFloat(amount)) {
		proceedPay('half');
	} else {
		$('#confirm-modal').modal('show');
	}
});

$('.btn-confirm').on('click', function() {
	var type = $(this).data('payment-type');
	if (type == 'full' || type == 'half') {
		proceedPay(type);		
	} else {
		errorMessage('Invalid payment type');
	}
});

$('.btn-payment').on('click', function() {
	resId = $(this).data('rev-id');
	$.post(paymentUrl, {id: resId}, function(data) {
		if (data['result']) {
			$('#payments-modal').find('.modal-body').html(data['view']);
			$('#payments-modal').modal('show');
		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');
});

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

function proceedPay(type) {
	successMessage('Please wait while connecting to paypal');
	$.post(rentUrl, {id: resId, type: type}, function(data) {
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
}

function closePaypal()  {
	dgFlow.closeFlow();
	// top.close();
	location.reload();
}