<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <p><a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a></p>
    </div>
   </div>
    <div id="message" class="alert alert-success text-center" hidden><strong>Successfully</strong> Added to your My Interests list.</div>
    <?php if(empty($items)): ?>
      <div class="col-md-12">
        <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> No item found. </div>
      </div>
    <?php else: ?>
      <?php foreach($items as $item):?>
        <div class="col-sm-3 col-lg-3 col-md-3">
            <div class="thumbnail">
            <?php
              $itemPic = $item['info']->item_pic == NULL ? 
                        'http://placehold.it/320x150' :
                        'data:image/jpeg;base64,'.base64_encode($item['info']->item_pic);
            ?>
                <img src="<?php echo $itemPic; ?>" alt="" style="width:320px; height:150px;">
                <div class="caption">
                    <h4><a href="#"><?php echo $item['info']->item_desc; ?></a></h4>
                    <p>₱ <?php echo number_format($item['info']->item_rate, 2) . '/pcs'; ?></p>
                    <?php if (isset($item['info']->shop_name)) { ?>
                    <p><?php echo $item['info']->shop_name . ' - ' . $item['info']->shop_branch; ?></p>
                    <?php } ?>
                    <p>
                      <?php 
                        if (!empty($item['categories'])) {
                          $label = "<span class='label label-default'>";
                          echo $label;
                          $categoryType = array();
                          foreach ($item['categories'] as $category) {
                            $categoryType[] = $category->category_type;
                          }
                          echo implode("</span>  $label", $categoryType);
                          echo "</span>";
                        }
                      ?>
                    </p>
                    <div class="btn-group">
                      <a href="<?php echo site_url('reservations/item/' . $item['info']->item_id); ?>" class="btn btn-info btn-xs">Reserve</a>
                      <button class="btn btn-success btn-xs btn-rent" data-item-id="<?php echo $item['info']->item_id; ?>">Rent</button>
                      <a class="btn btn-primary btn-xs my-interest-trigger" data-item-id="<?php echo $item['info']->item_id; ?>" data-interest-name="<?php echo $item['info']->item_desc; ?>" href="<?php echo $action; ?>" <?php echo (in_array($item['info']->item_id,$myinterests)) ? 'disabled=disabled' : ''; ?>><span class="fa fa-plus-circle"> <?php echo (in_array($item['info']->item_id,$myinterests)) ? 'Added ' : 'My Interest '; ?></span></a>
                    </div>
                </div>
            </div>
        </div>
      <?php endforeach; ?>
      <!--pagination-->
    <?php endif;?>
    <div class="text-center col-lg-12">
      <?php echo empty($pagination) ? '' : $pagination; ?>
    </div>
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