<div class="container-fluid">
  <div class="row no-gutter">
		<table id="subscription-table" class="table">
			<thead>
				<tr>
					<th>DATE</th>
					<th>PLAN</th>
					<th>AMOUNT</th>
					<th>SUBSCRIBER</th>
					<th class="text-center">OPTIONS</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($subscriptions as $subs) { ?>
				<tr data-subs-row="<?php echo $subs->subscription_id; ?>">
					<td><?php echo $subs->start_date; ?></td>
					<td><?php echo $subs->plan_name; ?></td>
					<td><?php echo $subs->subscription_amt; ?></td>
					<td><?php echo $subs->subscriber_fname . ' ' . $subs->subscriber_midint; ?></td>
					<td class="text-center">
						<div class="btn-group btn-group">
							<button class="btn btn-xs btn-success btn-active" data-subs-id="<?php echo $subs->subscription_id ?>">Activate</button>
							<button class="btn btn-xs btn-primary btn-disapprove" data-subs-id="<?php echo $subs->subscription_id ?>">Disapprove</button>
						</div>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
  </div>
</div>
<script>
	var activateUrl = "<?php echo site_url('Subscriptions/activate'); ?>";
	var disapproveUrl = "<?php echo site_url('Subscriptions/disapprove'); ?>";
</script>