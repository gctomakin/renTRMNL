<div class="container-fluid">
	<div class="row no-gutter">
		<table id="reservation-table" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>LESSEE</th>
					<th>SHOP</th>
					<th>DATE</th>
					<th>DURATION</th>
					<th class="text-right">TOTAL</th>
					<th class="text-right">PENALTY</th>
					<th class="text-right">BALANCE</th>
					<th>STATUS</th>
					<th class="text-center" width="180">OPTION</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($reservations as $rev) { ?>
				<tr data-reservation="<?php echo $rev->reserve_id; ?>">
					<td><?php echo $rev->reserve_id; ?></td>
					<td><?php echo $rev->lessee_fname; ?></td>
					<td><?php echo $rev->shop_name ; ?></td>
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
					<td class="status"><?php echo $rev->rent_status; ?></td>
					<td class="text-center">
						<div class="btn-group" role="group" aria-label="reservation-options">
							<button class="btn btn-success btn-xs btn-approve" data-rev-id="<?php echo $rev->reserve_id?>">approve</button>
							<button class="btn btn-primary btn-xs btn-disapprove" data-rev-id="<?php echo $rev->reserve_id?>">disapprove</button>
							<button class="btn btn-default btn-xs btn-view" data-rev-id="<?php echo $rev->reserve_id?>">details</button>
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
				<h4 class="modal-title" id="reservation-modal-title" >Rerservation's details</i></h4>
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
	var reservationDetailUrl = "<?php echo site_url('reservations/detail'); ?>";
	var approveUrl = "<?php echo site_url('reservations/approve'); ?>";
	var disapproveUrl = "<?php echo site_url('reservations/disapprove'); ?>";
</script>