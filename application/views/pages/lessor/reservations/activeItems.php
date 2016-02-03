<div class="container-fluid">
	<div class="row no-gutter">
		<?php if (empty($items)) { ?>
	  <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> No Rented Item now. </div>
    <?php } else { ?>
			<?php foreach($items as $item) { ?>
			<div class="col-sm-3 col-lg-3 col-md-3">
		    <div class="thumbnail">
		    	<p class="text-center">Reservation # : <?php echo $item['reserve_id'];?></p>
		      <img src="<?php echo $item['item_pic']; ?>" alt="" style="width:320px; height:150px;">
		      <div class="caption">
	          <h4><a href="#"><?php echo $item['item_desc']; ?></a></h4>
	          <p>
	          	₱ <?php echo number_format($item['item_rate'], 2) . '/pcs'; ?>
	          	<br>
	          	<?php echo number_format($item['qty']); ?> rented
	          </p>
	          <hr>
	          <p>
	          	<?php echo $item['duration']; ?> <br>
	          	<b><?php echo $item['reserve_by']; ?></b> <br>
	          	<a href="#" title="Shop"><?php echo $item['shop']; ?> </a>
	          </p>
	          <div class="btn-group">
	          </div>
		      </div>
		    </div>
	  	</div>
	  	<?php } ?>
	  	<div class="col-md-12 text-center">
	  	<?php echo empty($pagination) ? '' : $pagination; ?>
	  	</div>
	  <?php } ?>
	</div>
</div>