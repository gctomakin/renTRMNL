<div class="container-fluid">
	<div class="row no-gutter">
		<?php if (empty($subscriber['subscriber_paypal_account'])) { ?>
		<div class="col-lg-12">
			<p class="alert alert-danger">
				<b>Hey!!</b> You must setup your papypal account first, so lessee may pay you in your paypal account. 
				Change your Account Settings <a href="<?php echo site_url('lessor/account'); ?>">here.</a>
			</p>
		</div>
		<?php } ?>
		<div class="col-lg-6">
			<div class="panel panel-red">
				<div class="panel-heading">CURRENT SUBSCRIPTION</div>
				<div class="panel-body">
					<h3><?php echo $subscription['plan_name'];?></h3>
					<?php
						echo date('M d, Y', strtotime($subscription['start_date'])) . ' - ' . date('M d, Y', strtotime($subscription['end_date']))
					?>
					<a href="<?php echo site_url('lessor/subscriptions'); ?>" class="pull-right">Go to history</a>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<p class="text-right">Welcome, <b><?php echo ucfirst($subscriber['subscriber_fname']); ?></b></p>
		</div>
		<div class="col-lg-12">
			<div class="panel panel-red">
				<div class="panel-heading">ITEM CURRENTLY RENTED</div>
				<div class="panel-body">
				<?php if (empty($rentedItems)) { ?>
					<p>No item rented as of now.</p>
				<?php } else { ?>
					<table class="table">
						<thead>
							<tr>
								<th>DESCRIPTION</th>
								<th>AMOUNT</th>
								<th>QUANTITY</th>
								<th>DURATION</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($rentedItems as $item) { ?>
							<tr>
								<td><a href="#"><?php echo $item['item_desc']; ?></a></td>
								<td><?php echo $item['rental_amt']; ?></td>
								<td><?php echo $item['qty']; ?></td>
								<td><?php echo $item['duration']; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-lg-12"><hr></div>
		<div class="col-lg-6">
			<div class="panel panel-default">
				<div class="panel-heading">RECENTLY ADDED SHOP</div>
				<div class="panel-body">
				<?php if (empty($shops['data'])) { ?>
					<p>No Shop added as of now.</p>
				<?php } else { ?>
					<table class="table">
						<thead>
							<tr>
								<th>NAME</th>
								<th>ADDRESS</th>
								<th>STATUS</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($shops['data'] as $shop) { ?>
							<tr>
								<td><?php echo $shop->shop_name . ' ' . $shop->shop_branch ?></td>
								<td><?php echo $shop->address ?></td>
								<td><?php echo $shop->status ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<a class="pull-right" href="<?php echo site_url('lessor/shops/list') ?>">Shops</a>
				<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="panel panel-default">
				<div class="panel-heading">RECENTLY ADDED ITEM</div>
				<div class="panel-body">
				<?php if (empty($items)) { ?>
				<p>No Items added as of now.</p>
				<?php } else { ?>
					<table class="table">
						<thead>
							<tr>
								<th>DESCRIPTION</th>
								<th>RATE</th>
								<th>MODE</th>
								<th>QUANTITY</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($items as $item) { ?>
								<tr>
									<td><?php echo $item->item_desc; ?></td>
									<td><?php echo $item->item_rate; ?></td>
									<td><?php echo $modes[$item->item_rental_mode]; ?></td>
									<td><?php echo $item->item_qty; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<a class="pull-right" href="<?php echo site_url('lessor/items/list') ?>">Items</a>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>