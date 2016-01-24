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
	$('reservation-modal').modal('show');
});