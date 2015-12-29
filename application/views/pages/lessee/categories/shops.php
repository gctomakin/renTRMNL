<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <a href="<?php echo site_url('lessees');?>" class="text-center"><i class="fa fa-dashboard"></i> Go back to dashboard </a>
    </div>
  </div>
  <?php if(empty($shops)): ?>
    <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no shops added yet. </div>
  <?php else: ?>
    <hr>
    <?php foreach($shops as $shop):?>
    <div class="row no-gutter">
          <div class="col-md-7">
              <a href="#">
                  <img class="img-responsive" src="http://placehold.it/700x300" alt="">
              </a>
          </div>
          <div class="col-md-5">
              <h3><?php echo $shop->shop_name; ?></h3>
              <h4><?php echo $shop->shop_branch; ?></h4>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium veniam exercitationem expedita laborum at voluptate. Labore, voluptates totam at aut nemo deserunt rem magni pariatur quos perspiciatis atque eveniet unde.</p>
              <a class="btn btn-info" href="#">More Info <span class="glyphicon glyphicon-chevron-right"></span></a>
              <a class="btn btn-primary" href="#">My Shop <span class="glyphicon glyphicon-chevron-right"></span></a>
          </div>
      </div>
      <hr>
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
  <?php endif; ?>
  </div>
</div>