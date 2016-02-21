<div class="container-fluid">
	<div class="row no-gutter">
		<table id="reservation-table" class="table table-hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>DATE</th>
					<th>DURATION</th>
					<th>SHOP</th>
					<th  width="50">ITEM</th>
					<th class="text-right">TOTAL</th>
					<th class="text-right">PENALTY</th>
					<th class="text-right">BALANCE</th>
					<th>STATUS</th>
					<th class="text-center">OPTION</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($reservations as $rev) { ?>
				<tr data-reservation="<?php echo $rev['info']->reserve_id; ?>">
					<td><?php echo $rev['info']->reserve_id; ?></td>
					<td><?php echo date('M d, Y', strtotime($rev['info']->reserve_date)); ?></td>
					<td>
						<?php 
							echo date('M d, Y', strtotime($rev['info']->date_rented)) . ' - ';
							echo date('M d, Y', strtotime($rev['info']->date_returned)); 
						?>
					</td>
					<td><?php echo $rev['info']->shop_name; ?></td>
					<td><?php echo implode(', ', $rev['detail']); ?>
					</td>
					<td class="text-right total-amount"><?php echo $rev['info']->total_amt; ?></td>
					<td class="text-right"><?php echo $rev['info']->penalty; ?></td>
					<td class="text-right total-balance"><?php echo $rev['info']->total_balance; ?></td>
					<td class="status"><?php echo $rev['info']->status; ?></td>
					<td class="text-center">
						<div class="btn-group" role="group" aria-label="reservation-options">
							<?php if ($rev['info']->status == 'approve' && $rev['info']->total_balance > 0) { ?>
							<button class="btn btn-success btn-xs btn-rent" data-rev-id="<?php echo $rev['info']->reserve_id?>">pay</button>
							<?php } else if ($rev['info']->status == 'pending') { ?>
							<button class="btn btn-primary btn-xs btn-cancel" data-rev-id="<?php echo $rev['info']->reserve_id?>">cancel</button>
							<?php }?>
							<button class="btn btn-default btn-xs btn-view" data-rev-id="<?php echo $rev['info']->reserve_id?>">details</button>
							<button class="btn btn-danger btn-xs btn-payment" data-rev-id="<?php echo $rev['info']->reserve_id?>">payments</button>
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div id="confirm-modal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Pay Rental Confirmation</h4>
      </div>
      <div class="modal-body">
      	<h3>Choose payment type</h3>
      	<p><small>(There will be an additional cash bond for the first payment)</small></p>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default btn-confirm" data-payment-type="full" data-dismiss="modal">Full Payment</button>
      	<button type="button" class="btn btn-default btn-confirm" data-payment-type="half" data-dismiss="modal">Half Payment</button>
      </div>
    </div>
  </div>
</div>
<div class="modal modal-fullscreen fade" tabindex="-1" id="reservation-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="reservation-modal-title" >Rerservation's Details</h4>
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
<div class="modal modal-fullscreen fade" tabindex="-1" id="payments-modal">
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
<form id='paypal-form' class="standard" action="https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame">
  <input id="type" type="hidden" name="expType" value="light">
  <input id="paykey" type="hidden" name="paykey" value="">
  <input class="hidden" type="submit" id="btn-pay"> 
</form>
<script src="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script>
<script>
  var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'btn-pay'});
	var reservationCancelUrl = "<?php echo site_url('reservations/cancel'); ?>";
	var reservationDetailUrl = "<?php echo site_url('reservations/detail'); ?>";
	var rentUrl = "<?php echo site_url('rental/pay'); ?>";
	var paymentUrl = "<?php echo site_url('rental/details'); ?>"
</script>