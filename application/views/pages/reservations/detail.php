<div class="container-fluid">
  <div class="row no-gutter">
    <?php foreach($details as $detail):?>
      <div class="col-sm-3 col-lg-3 col-md-3">
        <div class="thumbnail">
        	<?php 
        		$itemPic = $detail->item_pic == NULL ? 
	        		'http://placehold.it/320x150' : 
	        		'data:image/jpeg;base64,'.base64_encode($detail->item_pic);
	        ?>
          <img src="<?php echo $itemPic; ?>" alt="">
          <div class="caption">
              <h4><a href="#"><?php echo $detail->item_desc; ?></a></h4>
              <dl>
                <dt>Item Rate</dt>
                <dd><?php echo number_format($detail->item_rate, 2); ?></dd>
                <dt>Quantity</dt>
                <dd><?php echo $detail->qty; ?></dd>
                <dt>TOTAL</dt>
                <dd><?php echo number_format($detail->qty * $detail->item_rate, 2); ?></dd>
              </dl>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
	</div>
</div>