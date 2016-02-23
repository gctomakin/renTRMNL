$(document).ready(function() {
	$('#reservation-table').DataTable();
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

$('.btn-approve').on('click', function() {
	var id = $(this).data('rev-id');
	if (confirm('Are you sure to approve this reservation?')) {
		proccessAction(approveUrl, {id:id}).then(function(status) {
			$('[data-reservation="'+ id +'"]').find('.status').text('approve');
		});
	}
});

$('.btn-disapprove').on('click', function() {
	var id = $(this).data('rev-id');
	if (confirm('Are you sure to disapprove this reservation?')) {
		proccessAction(disapproveUrl, {id:id}).then(function(status) {
			$('[data-reservation="'+ id +'"]').find('.status').text('disapprove');
		});
	}
});

$('.btn-payment').on('click', function() {
	var resId = $(this).data('rev-id');
	$.post(paymentUrl, {id: resId}, function(data) {
		if (data['result']) {
			$('#payments-modal').find('.modal-body').html(data['view']);
			$('#payments-modal').modal('show');
		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');
});

$('.btn-close').on('click', function() {
	var resId = $(this).data('rev-id');
	if (confirm('Close this reservation?')) {
		proccessAction(closeUrl, {id: resId}).then(function(status) {
			location.reload();
		}); 
	}
});

$('.btn-penalty').on('click', function() {
	var button = $(this);
	var resId = button.data('rev-id');
	if (confirm('Add penalty for this reservation?')) {
		$.post(penaltyUrl, {resId: resId}, function(data) {
			if (data['result']) {
				successMessage(data['message']);
				$("[data-reservation='"+resId+"']").find('.penalty').text(data['penalty']);
				var balanceTd = $("[data-reservation='"+resId+"']").find('.balance');
				var balance = balanceTd.text();
				balanceTd.text(data['penalty'] + parseFloat(balance));
				button.remove();
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	}
});