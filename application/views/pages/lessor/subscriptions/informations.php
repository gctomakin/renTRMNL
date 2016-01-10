<div class="container-fluid">
  <div class="row-centered">
  	<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 col-centered">	
			<!-- PRICE ITEM -->
			<div class="panel price panel-red">
				<div class="panel-heading  text-center">
					<h3 style="color:#fff;">Current</h3>
				</div>
				<div class="panel-body text-center">
					<p class="lead" style="font-size:3rem;"><?php echo $plan['plan_name']; ?> - <strong>₱ <?php echo number_format($plan['plan_rate'], 2); ?></strong></p>
				</div>
				<ul class="list-group list-group-flush text-center">
					<li class="list-group-item"><i class="icon-ok text-danger"></i> <?php echo $plan['plan_desc']; ?></li>
				</ul>
				<div class="panel-footer" style="color:#888;">
					<?php
						switch($plan['status']) {
							case 'active': $statusClass = 'text-success'; break;
							case 'inactive': $statusClass = 'text-danger'; break;
							case 'pending': $statusClass = 'text-warning'; break;
						}
					?>
					<span class="pull-right <?php echo $statusClass; ?>"><?php echo ucfirst($plan['status']); ?></span>
					<p>Duration : <?php echo date('M d, Y', strtotime($plan['start_date'])); ?> - <?php echo date('M d, Y', strtotime($plan['end_date'])); ?></p>
				</div>
			</div>
			<!-- /PRICE ITEM -->
		</div>
	</div>
	<div class="row-centered text-center">
		<div class="col-lg-12">
			<a href="javascript:;" id="subs-history-show">History</a>
		</div>
		<div class="col-lg-12" id="subs-history-container" style="display:none;">
			<table id="subs-history-table" class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center">STATUS</th>
						<th class="text-center">START</th>
						<th class="text-center">END</th>
						<th class="text-center">AMOUNT</th>
						<th class="text-center">QTY</th>
						<th class="text-center">PLAN</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($subscriptions as $subs) { ?>
					<tr>
						<td><?php echo $subs->status; ?></td>
						<td><?php echo $subs->start_date; ?></td>
						<td><?php echo $subs->end_date; ?></td>
						<td><?php echo $subs->subscription_amt; ?></td>
						<td><?php echo $subs->qty; ?></td>
						<td><?php echo $subs->plan_name; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>