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
		proccessAction(approveUrl, id).then(function(status) {
			$('[data-reservation="'+ id +'"]').find('.status').text('approve');
		});
	}
});

$('.btn-disapprove').on('click', function() {
	var id = $(this).data('rev-id');
	if (confirm('Are you sure to disapprove this reservation?')) {
		proccessAction(disapproveUrl, id).then(function(status) {
			$('[data-reservation="'+ id +'"]').find('.status').text('disapprove');
		});
	}
});