$(document).ready(function() {
	$('#category-table').DataTable();
}); 

$('.btn-delete').on('click', function() {
	var id = $(this).data('category-id');
	if (confirm('Are you sure to delete this category?')) {
		proccessAction(deleteUrl, {id:id}).then(function(data) {
			$('[data-category-row="'+id+'"]').fadeOut('slow');
		});
	}
});