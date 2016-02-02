<table class="table">
	<thead>
		<tr>
			<th>DATE</th>
			<th>DESCRIPTION</th>
			<th class="text-right">AMOUNT</th>
			<th>STATUS</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($payments as $payment) { ?>
		<tr data-pay-id="<?php echo $payment->payment_id; ?>">
			<td><?php echo date('M d, Y H:i a', strtotime($payment->payment_date)); ?></td>
			<td><?php echo $payment->payment_description; ?></td>
			<td class="text-right"><?php echo $payment->payment_amt; ?></td>
			<td class="status"><?php echo $payment->payment_status; ?></td>
			<td>
				<?php if ($payment->payment_status == 'pending') { ?>
					<?php if (isset($isLessor)) { ?>
				<button class="btn btn-xs btn-success btn-payment-detail btn-pay-receive">receive</button>	
					<?php }?>
				<button class="btn btn-xs btn-primary btn-payment-detail btn-pay-cancel">cancel</button>
				<?php } ?>
				<input type="hidden" class="pay-id" value="<?php echo $payment->payment_id ?>">
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<script type="text/javascript">
	var cancelUrl = "<?php echo $cancelUrl; ?>";
	var receiveUrl = "<?php echo $receiveUrl; ?>"
	$(document).ready(function() {
		$('.btn-pay-cancel').click(function() {
			if (confirm('Are you sure to cancel this payment?')) {
				var id = $(this).siblings('.pay-id').val();
				proccessAction(cancelUrl, {id: id}).then(function() {
					$('[data-pay-id="'+id+'"]').find('.status').text('cancel');
					$('.btn-payment-detail').hide();
				});
			}
		});
		$('.btn-pay-receive').click(function() {
			if (confirm('Are you sure that you receive this payment?')) {
				var id = $(this).siblings('.pay-id').val();
				proccessAction(receiveUrl, {id: id}).then(function() {
					$('[data-pay-id="'+id+'"]').find('.status').text('receive');
					$('.btn-payment-detail').hide();
				});
			}
		});
	}); 
</script>