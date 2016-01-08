<table class="table">
	<tr>
		<td>Subscription Plan</td>
		<td><?php echo $sub['plan_name']; ?></td>
	</tr>
	<tr>
		<td>Start Date</td>
		<td><?php echo $sub['start_date']; ?></td>
	</tr>
	<tr>
		<td>End Date</td>
		<td><?php echo $sub['end_date']; ?></td>
	</tr>
	<tr>
		<td>Amount</td>
		<td><?php echo number_format($sub['subscription_amt'], 2); ?></td>
	</tr>
	<tr>
		<td>Quantity</td>
		<td><?php echo  $sub['qty']; ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td><?php echo $sub['status']; ?></td>
	</tr>
</table>
<a href="<?php echo site_url('lessors'); ?>">Go to Dashboard.</a>