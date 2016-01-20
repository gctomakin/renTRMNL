<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <p><a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a></p>
    </div>
   </div>
    <div id="message" class="alert alert-success text-center" hidden><strong>Successfully</strong> Added to your My Interests list.</div>
    <?php if(empty($items)): ?>
      <div class="col-md-12">
        <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no items added yet. </div>
      </div>
    <?php else: ?>
      <?php foreach($items as $item):?>
        <div class="col-sm-3 col-lg-3 col-md-3">
            <div class="thumbnail">
                <img src="http://placehold.it/320x150" alt="">
                <div class="caption">
                    <h4><a href="#"><?php echo $item->item_desc; ?></a></h4>
                    <dl>
                      <dt>Item Rate</dt>
                      <dd><?php echo $item->item_rate; ?></dd>
                      <dt>Item Qty</dt>
                      <dd><?php echo $item->item_qty; ?></dd>
                      <dt>Shop Name</dt>
                      <dd><?php echo $item->shop_name; ?></dd>
                      <dt>Shop Branch</dt>
                      <dd><?php echo $item->shop_branch; ?></dd>
                    </dl>
                    <div class="btn-group">
                      <button class="btn btn-info btn-xs">Reserve</button>
                      <button class="btn btn-success btn-xs btn-rent" data-item-id="<?php echo $item->item_id; ?>">Rent</button>
                      <a class="btn btn-primary btn-xs my-interest-trigger" data-item-id="<?php echo $item->item_id; ?>" data-interest-name="<?php echo $item->item_desc; ?>" href="<?php echo $action; ?>" <?php echo (in_array($item->item_id,$myinterests)) ? 'disabled=disabled' : ''; ?>><span class="fa fa-plus-circle"> <?php echo (in_array($item->item_id,$myinterests)) ? 'Added ' : 'My Interest '; ?></span></a>
                    </div>
                </div>
            </div>
        </div>
      <?php endforeach; ?>
      <!--pagination-->
      <div class="row text-center">
        <div class="col-lg-12">
            <ul class="pagination">
                <li>
                    <a href="#">«</a>
                </li>
                <li class="active">
                    <a href="#">1</a>
                </li>
                <li>
                    <a href="#">2</a>
                </li>
                <li>
                    <a href="#">3</a>
                </li>
                <li>
                    <a href="#">4</a>
                </li>
                <li>
                    <a href="#">5</a>
                </li>
                <li>
                    <a href="#">»</a>
                </li>
            </ul>
        </div>
      </div>
    <?php endif;?>
  </div>
</div>
<form id='paypal-form' class="standard" action="https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame">
  <input id="type" type="hidden" name="expType" value="light">
  <input id="paykey" type="hidden" name="paykey" value="">
  <input class="hidden" type="submit" id="btn-pay"> 
</form>
<script src="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script>
<script>
  var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'btn-pay'});
  var rentalPayUrl = "<?php echo site_url('rental/pay'); ?>";
</script>