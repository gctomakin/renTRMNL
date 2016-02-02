$(document).ready(function() {
	$('#payment-table').DataTable();
});

$('.btn-view').on('click', function() {
	var id = $(this).siblings('.pay-id').val();
	var revId = $(this).siblings('.rev-id').val();
	$.post(reserveDetailUrl, {id:revId}, function(data) {
		if (data['result']) {
			$('#payment-modal').find('.modal-body').html(data['view']);
			$('#payment-modal').modal('show');
		} else {
			errorMessage(data['message']);
		}
	}, 'JSON');
});

$('.btn-receive').on('click', function() {
	var id = $(this).siblings('.pay-id').val();
	var revId = $(this).siblings('.rev-id').val();
	if (confirm('Are you sure to approve this payment?')) {
		proccessAction(approveUrl, {id:id}).then(function(status) {
			$('[data-payment="'+ id +'"]').fadeOut('slow');
		});
	}
});

$('.btn-cancel').on('click', function() {
	var id = $(this).siblings('.pay-id').val();
	var revId = $(this).siblings('.rev-id').val();
	if (confirm('Are you sure to cancel this payment?')) {
		proccessAction(cancelUrl, {id:id}).then(function(status) {
			$('[data-payment="'+ id +'"]').fadeOut('slow');
		});
	}
});