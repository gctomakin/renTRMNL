$(document).ready(function() {
	$('#subs-history-show').click(function() {
		$('#subs-history-container').slideToggle('show');
		$('#subs-history-table').DataTable();
	});
}); 