<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <p><a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a></p>
    </div>
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
                      <a href="#" class="btn btn-info btn-xs">Reserve</a>
                      <a href="#" class="btn btn-success btn-xs">Rent</a>
                      <a href="#" class="btn btn-primary btn-xs">My Interest</a>
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