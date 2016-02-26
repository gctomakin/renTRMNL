<table class="monitor-table table">
	<thead>
		<tr>
			<th>ID</th>
			<th>PIC</th>
			<th>SHOP</th>
			<th>BRANCH</th>
			<th>ADDRESS</th>
			<th>OWNER</th>
			<th>ITEMS</th>
			<th>STATUS</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($shops as $shop) { ?>
		<tr>
			<td><?php echo $shop->shop_id; ?></td>
			<td>
				<?php $shopPic = $shop->shop_image == NULL ? 
					'http://placehold.it/100x50' :
					'data:image/jpeg;base64,' . base64_encode($shop->shop_image);
				?>
				<img src="<?php echo $shopPic; ?>" alt="" style="width:100px; height: 50px;">
			</td>
			<td><?php echo $shop->shop_name; ?></td>
			<td><?php echo $shop->shop_branch; ?></td>
			<td><?php echo $shop->address; ?></td>
			<td><?php echo $shop->subscriber_id; ?></td>
			<td><?php echo $shop->total_item; ?></td>
			<td><?php echo $shop->status; ?></td>
			<td></td>
		</tr>
		<?php } ?>
	</tbody>
</table>