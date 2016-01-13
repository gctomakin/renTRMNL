<table id="lessor-detail-table" class="table">
	<thead>
		<th>Name</th>
		<th>Email</th>
		<th>Date</th>
		<th>Status</th>
	</thead>
	<tbody>
		<?php 
			if (isset($subscribers)) {
				foreach($subscribers as $subs) {
		?>
		<tr>
			<td><?php echo $subs->subscriber_fname . ' ' . $subs->subscriber_lname; ?></td>
			<td><?php echo $subs->subscriber_email; ?></td>
			<td><?php echo date('M d, Y', strtotime($subs->date_registered)); ?></td>
			<td><?php echo $subs->subscriber_status; ?></td>
		</tr>
		<?php 
				}
			}
		?>
	</tbody>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$('#lessor-detail-table').DataTable({
			"paging":   false,
      "dom": '<"pull-left"f><"pull-right"l>tip',
      "info":     false
    });
	});
</script>