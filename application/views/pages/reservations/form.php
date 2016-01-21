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
  		<form action="" class="form-horizontal">
	  		<div class="form-group">
	  			<label class="control-label col-md-2" for="quantity">Quantity</label>
	  			<div class="col-md-8">
	  				<select id="quantity" name="quantity" class="form-control">
	  					<option value="" selected></option>
	  					<option value="1">1 PCS</option>
	  					<option value="2">2 PCS</option>
	  					<option value="3">3 PCS</option>
	  					<option value="4">4 PCS</option>
	  					<option value="5">5 PCS</option>
	  				</select>
	  			</div>
	  		</div>
	  		<div class="form-group">
	  			<label class="control-label col-md-2" for="date">Date</label>
	  			<div class="col-md-8">
	  				<input type="text" id="date" name="date" placeholder="Choose date to rent" class="single-datepicker form-control">
	  			</div>
	  		</div>
	  		<div class="form-group">
	  			<label class="control-label col-md-2" for="total">Total</label>
	  			<div class="col-md-8">
	  				<h4>200.00</h4>
	  				<input type="hidden" id="total">
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