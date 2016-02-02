<div class="container-fluid">
	<div class="row no-gutter">
		<table id="payment-table" class="table">
			<thead>
				<tr>
					<th>DATE</th>
					<th>RESERVE ID</th>
					<th>DESCRIPTION</th>
					<th class="text-right">AMOUNT</th>
					<th class="text-center">OPTION</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($payments as $pay) { ?>
				<tr data-payment="<?php echo $pay->payment_id; ?>">
					<td><?php echo date('M d, Y', strtotime($pay->payment_date)); ?></td>
					<td><?php echo $pay->reserve_id; ?></td>
					<td><?php echo $pay->payment_description . ' Payment'; ?></td>
					<td class="text-right"><?php echo $pay->payment_amt; ?></td>
					<td class="text-center">
						<div class="btn-group" role="group" aria-label="payment-options">
							<button class="btn btn-success btn-xs btn-receive">receive</button>
							<button class="btn btn-primary btn-xs btn-cancel">cancel</button>
							<button class="btn btn-default btn-xs btn-view">view details</button>
							<input type="hidden" class="pay-id" value="<?php echo $pay->payment_id?>">
							<input type="hidden" class="rev-id" value="<?php echo $pay->reserve_id?>">
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="modal modal-fullscreen fade" id="payment-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="payment-modal-title" >Rerservation's details</i></h4>
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

<script type="text/javascript">
	var reserveDetailUrl = "<?php echo site_url('reservations/detail'); ?>";
	var approveUrl = "<?php echo site_url('rental/changeStatus/receive'); ?>";
	var cancelUrl = "<?php echo site_url('rental/changeStatus/cancel'); ?>";
</script>