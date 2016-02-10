<div class="container-fluid">
  <div class="row no-gutter">
  	<div class="col-md-12">
  		<table class="table">
  			<thead>
  				<tr>
  					<th>DESCRIPTION</th>
  					<th>AMOUNT</th>
  					<th>QUANTITY</th>
  					<th>DURATION</th>
  					<th>SHOP</th>
  					<th>OPTION</th>
  				</tr>
  			</thead>
  			<tbody>
  				<?php foreach ($items as $item) { ?>
  				<tr>
  					<td><a href="<?php echo site_url('lessee/items/?item=' . $item['item_desc']); ?>" target="_blank"><?php echo $item['item_desc']; ?></a></td>
  					<td><?php echo $item['rental_amt']; ?></td>
  					<td><?php echo $item['qty']; ?></td>
  					<td><?php echo $item['duration']; ?></td>
  					<td><?php echo $item['shop']; ?></td>
  					<td>
  						<button class="btn btn-xs btn-primary">return</button>
  						<button class="btn btn-xs btn-default">view transaction</button>
  					</td>
  				</tr>
  				<?php } ?>
  			</tbody>
  		</table>
  	</div>
	</div>
</div>