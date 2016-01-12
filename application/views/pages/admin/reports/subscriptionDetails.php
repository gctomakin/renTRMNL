<table id="subscription-detail-table" class="table">
	<thead>
		<th>Date</th>
		<th>Plan</th>
		<th>Amount</th>
	</thead>
	<tbody>
		<?php 
			if (isset($subscriptions)) {
				foreach($subscriptions as $subs) {
		?>
		<tr>
			<td><?php echo date('M d, Y', strtotime($subs->start_date)); ?></td>
			<td><?php echo $subs->plan_name; ?></td>
			<td class="text-right"><?php echo number_format($subs->subscription_amt, 2); ?></td>
		</tr>
		<?php 
				}
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="2">
				Subtotal:
			</th>
			<th id="total-amount" class="text-right"></th>
		</tr>
	</tfoot>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$('#subscription-detail-table').DataTable({
			"paging":   false,
      "dom": '<"pull-left"f><"pull-right"l>tip',
      "info":     false,
      fnFooterCallback: function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
        var total = 0;
        for ( var i = iStart; i < iEnd ; i++ ) {
            // console.log(total, aaData[ aiDisplay[i] ][2].replace(',',''));
          total += parseFloat(aaData[ aiDisplay[i] ][2].replace(',',''));
        }
        $('#total-amount').text(formatNumber(total));
      }
    });
	});
</script>