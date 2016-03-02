<div class="container-fluid">
  <div class="row no-gutter">
  	<div class="col-md-12">
  		<table class="table">
  			<thead>
  				<tr>
            <th>RESERVATION # </th>
  					<th>DESCRIPTION</th>
  					<th>AMOUNT</th>
  					<th>QUANTITY</th>
  					<th>DURATION</th>
  					<th>SHOP</th>
  					<th></th>
  				</tr>
  			</thead>
  			<tbody>
          <?php if (empty($items)) { ?>
          <tr><td colspan="7"><h2 class="text-danger">No Item Rented as of now.</h2></td></tr>
          <?php } else { ?>
    				<?php foreach ($items as $item) { ?>
    				<tr>
              <td>
                <a href="<?php echo site_url('lessee/reserved?id=' . $item['reserve_id']); ?>">
                  <?php echo $item['reserve_id']; ?>
                </a>
              </td>
    					<td><a href="<?php echo site_url('lessee/items/?item=' . $item['item_desc']); ?>" target="_blank"><?php echo $item['item_desc']; ?></a></td>
    					<td><?php echo $item['rental_amt']; ?></td>
    					<td><?php echo $item['qty']; ?></td>
    					<td><?php echo $item['duration']; ?></td>
    					<td><?php echo $item['shop']; ?></td>
    					<td>
                <?php if ($item['total_balance'] == 0) { ?>
    						<button class="btn btn-success btn-xs btn-return" data-rev-id="<?php echo $item['reserve_id']; ?>">return</button>
                <?php } ?>
    						<!-- <button class="btn btn-default btn-xs btn-reservation" data-rev-id="<?php echo $item['reserve_id']; ?>">view transaction</button> -->
    					</td>
    				</tr>
    				<?php } ?>
          <?php } ?>
  			</tbody>
  		</table>
  	</div>
	</div>
</div>
<div class="modal modal-fullscreen fade" tabindex="-1" id="reservation-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" >Payment's Details</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  var returnUrl = "<?php echo site_url('reservations/returnStatus'); ?>"
</script>