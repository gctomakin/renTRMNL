<table id="user-detail-table" class="table">
	<thead>
		<th>USER</th>
		<th>NAME</th>
		<th>EMAIL</th>
		<th>DATE</th>
		<th>STATUS</th>
	</thead>
	<tbody>
		<?php 
			if (isset($lessors)) {
				foreach($lessors as $lessor) {
		?>
		<tr>
			<td>Lessor</td>
			<td><?php echo $lessor->subscriber_fname . ' ' . $lessor->subscriber_lname; ?></td>
			<td><?php echo $lessor->subscriber_email; ?></td>
			<td><?php echo date('M d, Y', strtotime($lessor->date_registered)); ?></td>
			<td><?php echo $lessor->subscriber_status; ?></td>
		</tr>
		<?php 
				}
			}
		?>
		<?php 
			if (isset($lessees)) {
				foreach($lessees as $lessee) {
		?>
		<tr>
			<td>Lessee</td>
			<td><?php echo $lessee->lessee_fname . ' ' . $lessee->lessee_lname; ?></td>
			<td><?php echo $lessee->lessee_email; ?></td>
			<td><?php echo date('M d, Y', strtotime($lessee->date_registered)); ?></td>
			<td><?php echo $lessee->status; ?></td>
		</tr>
		<?php 
				}
			}
		?>
	</tbody>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$('#user-detail-table').DataTable({
			"paging":   false,
      "dom": '<"pull-left"f><"pull-right"l>tip',
      "info":     false
    });
	});
</script>