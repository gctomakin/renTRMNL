<table class="monitor-table table">
	<thead>
		<tr>
			<th>ID</th>
			<th>PIC</th>
			<th>DESCIPTION</th>
			<th>RATE</th>
			<th>QUANTITY</th>
			<th>STATUS</th>
			<th>RENTED</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($items as $item) { ?>
		<tr>
			<td><?php echo $item['item_id']; ?></td>
			<td><img src="<?php echo $item['item_pic']; ?>" alt="" style="width:100px; height: 50px;"></td>
			<td><?php echo $item['item_desc']; ?></td>
			<td><?php echo $item['item_rate']; ?></td>
			<td><?php echo $item['item_qty']; ?></td>
			<td><?php echo $item['item_stats']; ?></td>
			<td><?php echo $item['total_rented']; ?></td>
			<td></td>
		</tr>
		<?php } ?>
	</tbody>
</table>