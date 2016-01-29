<div class="container-fluid">
	<div class="row no-gutter">
		<table id="reservation-table" class="table">
			<thead>
				<tr>
					<th>DATE</th>
					<th>DURATION</th>
					<th class="text-right">TOTAL</th>
					<th class="text-right">PENALTY</th>
					<th class="text-right">BALANCE</th>
					<th>STATUS</th>
					<th class="text-center">OPTION</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($reservations as $rev) { ?>
				<tr data-reservation="<?php echo $rev->reserve_id; ?>">
					<td><?php echo date('M d, Y', strtotime($rev->reserve_date)); ?></td>
					<td>
						<?php 
							echo date('M d, Y', strtotime($rev->date_rented)) . ' - ';
							echo date('M d, Y', strtotime($rev->date_returned)); 
						?>
					</td>
					<td class="text-right"><?php echo $rev->total_amt; ?></td>
					<td class="text-right"><?php echo $rev->penalty; ?></td>
					<td class="text-right"><?php echo $rev->total_balance; ?></td>
					<td class="status"><?php echo $rev->status; ?></td>
					<td class="text-center">
						<div class="btn-group" role="group" aria-label="reservation-options">
							<?php if ($rev->status == 'approve') { ?>
							<button class="btn btn-success btn-xs btn-rent" data-rev-id="<?php echo $rev->reserve_id?>">pay for rent</button>
							<?php } else if ($rev->status != 'cancel' && $rev->status != 'paypal cancel') { ?>
							<button class="btn btn-primary btn-xs btn-cancel" data-rev-id="<?php echo $rev->reserve_id?>">cancel</button>
							<?php }?>
							<button class="btn btn-default btn-xs btn-view" data-rev-id="<?php echo $rev->reserve_id?>">view details</button>
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="modal modal-fullscreen fade" id="reservation-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="reservation-modal-title" >Rerservation's Details</i></h4>
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
	var rentUrl = "<?php echo site_url('reservations/pay'); ?>";
</script>