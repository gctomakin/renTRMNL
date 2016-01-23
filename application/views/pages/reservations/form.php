<div class="container-fluid">
	<div class="row no-gutter">
		<div class="col-lg-12">
			<p><a href="<?php echo site_url('lessee/items') ?>"><i class="fa fa-cubes"></i> Back to Items</a></p>
		</div>
		<div class="col-lg-6">
			<div class="media">
			  <div class="media-left">
			  	<a href="#">
			    	<img class="media-object" src="<?php echo $itemPic; ?>" alt="<?php echo $item['item_desc']; ?>" style="width:250px;">
			    </a>
			  </div>
			  <div class="media-body">
			    <h2 class="media-heading"><?php echo $item['item_desc']; ?></h2>
			    <p><b>Shop</b> : <a href="#">Shop 1</a> </p>
			    <p><b>Rate</b> : <?php echo number_format($item['item_rate'], 2); ?></p>
			    <p><b>Remaining</b> : <?php echo number_format($item['item_qty']); ?> pcs </p>
			    <p><b>Owner</b> : <a href="">LOL</a> </p>
			  </div>
			</div>
		</div>
  	<div class="col-lg-6">
  		<form action="<?php echo $action; ?>" id="reserve-form" method="POST" class="form-horizontal">
	  		<div class="form-group">
	  			<label class="control-label col-md-2" for="quantity">Quantity</label>
	  			<div class="col-md-8">
	  				<select id="quantity" name="quantity" class="form-control">
	  					<option value="" selected></option>
	  					<?php for($i = 1; $i <= $item['item_qty']; $i++) { ?>
							<option value="<?php echo $i; ?>"><?php echo $i . ' PCS'; ?></option>
	  					<?php } ?>
	  				</select>
	  			</div>
	  		</div>
	  		<div class="form-group">
	  			<label class="control-label col-md-2" for="date">Date</label>
	  			<div class="col-md-8">
	  				<input type="text" required id="date" name="date" readonly placeholder="Choose date to rent" class="form-control">
	  			</div>
	  		</div>
	  		<div class="form-group">
	  			<label class="control-label col-md-2" for="total">Total</label>
	  			<div class="col-md-8">
	  				<h4 id="total-info">0.00</h4>
	  				<input type="hidden" name="total" id="total" value="0">
	  				<input type="hidden" id="item-rate" value="<?php echo $item['item_rate']; ?>">
	  				<input type="hidden" id="start-date" value="<?php echo $startDate; ?>">
	  				<input type="hidden" name="itemId" id="item-id" value="<?php echo $item['item_id']; ?>">
	  			</div>
	  		</div>
	  		<div class="form-group">
	  			<div class="col-md-8 col-md-offset-2">
	  				<input type="submit" id="submit" value="Reserve" class="btn btn-primary">
	  			</div>
	  		</div>
	  	</form>
  	</div>
  </div>
</div>