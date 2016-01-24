<table class="table" id="shop-table">
	<thead>
		<tr>
			<th>SHOP</th>
			<th>BRANCH</th>
			<th>ADDRESS</th>
			<th>SUBSCRIBER</th>
			<th>STATUS</th>
			<th class="text-center">OPTION</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($shops as $shop) { ?>
		<tr data-shop-row="<?php echo $shop->shop_id; ?>">
			<td><?php echo $shop->shop_name; ?></td>
			<td><?php echo $shop->shop_branch; ?></td>
			<td><?php echo $shop->address; ?></td>
			<td><?php echo $shop->subscriber_id; ?></td>
			<td><?php echo $shop->status; ?></td>
			<td class="text-center">
				<div class="btn-group" role="group" aria-label="shop-options">
					<button class="btn btn-xs btn-success btn-approve" data-shop-id="<?php echo $shop->shop_id; ?>">approve</button>
					<button class="btn btn-xs btn-primary btn-disapprove" data-shop-id="<?php echo $shop->shop_id; ?>">disapprove</button>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<script>
	var approveUrl = "<?php echo site_url('rentalshops/approve'); ?>";
	var disapproveUrl = "<?php echo site_url('rentalshops/disapprove'); ?>";
</script>