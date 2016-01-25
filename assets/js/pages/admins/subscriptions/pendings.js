$(document).ready(function() {
	$('#subscription-table').DataTable();
}); 

$('.btn-active').on('click', function() {
	var id = $(this).data('subs-id');
	if (confirm("Are you sure to activate this subscription?")) {
		proccessAction(activateUrl, {id:id}).then(function(data) {
			$('[data-subs-row="'+id+'"]').fadeOut('slow');
		});
	}
});
$('.btn-disapprove').on('click', function() {
	var id = $(this).data('subs-id');
	if (confirm("Are you sure to disapprove this subscription?")) {
		proccessAction(disapproveUrl, {id:id}).then(function(data) {
			$('[data-subs-row="'+id+'"]').fadeOut('slow');
		});
	}
});