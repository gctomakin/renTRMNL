<div class="container-fluid">
  <div class="row no-gutter">
    <?php foreach($details as $detail):?>
      <div class="col-sm-3 col-lg-3 col-md-3">
        <div class="thumbnail">
        	<img src="<?php echo $detail['item_pic']; ?>" alt="<?php echo $detail['item_desc']; ?>" style="width:250; height:150px;">
          <div class="caption">
              <h4><a href="#"><?php echo $detail['item_desc']; ?></a></h4>
              <dl>
                <dt>Rental Amount</dt>
                <dd><?php echo number_format($detail['rental_amt'], 2); ?></dd>
                <dt>Quantity</dt>
                <dd><?php echo $detail['qty']; ?></dd>
                <dt>TOTAL</dt>
                <dd><?php echo number_format($detail['qty'] * $detail['item_rate'], 2); ?></dd>
              </dl>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
	</div>
</div>