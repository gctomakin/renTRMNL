<table class="monitor-table table">
	<thead>
		<tr>
			<th>ID</th>
			<th>FULLNAME</th>
			<th>CONTACT</th>
			<th>EMAIL</th>
			<th>STATUS</th>
			<th>REGISTERED</th>
			<th>SHOPS OWNED</th>
			<th>SUBSCRIPTIONS</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lessors as $lessor) { ?>
		<tr>
			<td><?php echo $lessor->subscriber_id; ?></td>
			<td><?php echo $lessor->subscriber_fname . ' ' . $lessor->subscriber_lname; ?></td>
			<td><?php echo $lessor->subscriber_telno; ?></td>
			<td><?php echo $lessor->subscriber_email; ?></td>
			<td><?php echo $lessor->subscriber_status; ?></td>
			<td><?php echo $lessor->date_registered; ?></td>
			<td><?php echo $lessor->total_shops; ?></td>
			<td><?php echo $lessor->total_subscriptions; ?></td>
			<td></td>
		</tr>
		<?php } ?>
	</tbody>
</table>