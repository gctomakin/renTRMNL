<div class="container-fluid">
	<div class="row no-gutter">
		<div class="col-lg-12">
			<a href="<?php echo site_url('lessor/rental/history') ?>"><i class="fa fa-tag"></i> Go to Rental history</a>
		</div>
		<hr>
		<div class="col-lg-12">
			<table id="item-report-table" class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>DESCRIPTION</th>
						<th>SHOP</th>
						<th>RENTED</th>
						<th>SALES</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $item) {
						$shop = empty($item->shop_name) ? 'No shop' : $item->shop_name . ' - ' . $item->shop_branch;
					?>
					<tr>
						<td><?php echo $item->item_id; ?></td>
						<td><?php echo $item->item_desc; ?></td>
						<td><?php echo $shop; ?></td>
						<td><?php echo number_format($item->rentedQty); ?></td>
						<td><?php echo number_format($item->rentedAmt, 2); ?></td>
						<td></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>