$(document).ready(function() {
	$('#reservation-table').DataTable({});
}); 

$('.btn-cancel').on('click', function() {
	var id = $(this).data('rev-id');
	if (confirm('Are you sure to cancel this reservation?')) {
		$.post(reservationCancelUrl, {id:id}, function(data) {
			if (data['result']) {
				$('[data-reservation="'+id+'"]').fadeOut('fast');
				successMessage(data['message']);
			} else {
				errorMessage(data['message']);
			}
		}, 'JSON');
	}
});

$('.btn-view').on('click', function() {
	$('reservation-modal').modal('show');
});