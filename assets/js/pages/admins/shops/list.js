$(document).ready(function() {
	$('#shop-table').DataTable();
});

$('.btn-delete').on('click', function() {
	var id = $(this).data('shop-id');
	if (confirm('Are you sure to delete this shop?')) {
		proccessAction(deleteUrl, {id: id}).then(function(data) {
			$('[data-shop-row="'+id+'"]').fadeOut('slow');
		});
	}
});