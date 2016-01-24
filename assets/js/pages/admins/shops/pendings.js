$(document).ready(function() {
	$('#shop-table').DataTable();	
});

$('.btn-approve').on('click', function() {
	var id = $(this).data('shop-id');
	if (confirm('Approve this shop?')) {
		proccessAction(approveUrl, {id:id}).then(function(data) {
			$('[data-shop-row="'+id+'"]').fadeOut('slow');
		});
	}
});

$('.btn-disapprove').on('click', function() {
	var id = $(this).data('shop-id');
	if (confirm('Disapprove this shop?')) {
		proccessAction(disapproveUrl, {id:id}).then(function(data) {
			$('[data-shop-row="'+id+'"]').fadeOut('slow');
		});
	}
});