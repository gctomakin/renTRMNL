<div class="container-fluid">
  <div class="row no-gutter">
    <a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a>
    <?php if(empty($items)): ?>
      <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no items added yet. </div>
    <?php else: ?>
      <hr>
      <?php foreach($items as $item):?>
        <div class="col-sm-3 col-lg-3 col-md-3">
            <div class="thumbnail">
                <img src="http://placehold.it/320x150" alt="">
                <div class="caption">
                    <div class="btn-group inline pull-right" data-toggle="buttons-checkbox">
                      <a href="#" class="btn btn-info btn-xs">Reserve</a>
                      <a href="#" class="btn btn-success btn-xs">Rent</a>
                      <a href="#" class="btn btn-primary btn-xs">My Interest</a>
                    </div>
                    <h4><a href="#">First Interest</a>
                    </h4>
                    <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
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
    <?php endif;?>
  </div>
</div>