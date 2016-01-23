$(document).ready(function() {
	$('#reservation-table').DataTable({});
}); 

$('.btn-cancel').on('click', function() {
	if (confirm('Are you sure to cancel this reservation?')) {
		console.log('cancelled');
	}
});

$('.btn-view').on('click', function() {
	$('reservation-modal').modal('show');
});