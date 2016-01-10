<div class="container-fluid">
        <div class="row no-gutter">
        <?php if(empty($categories)): ?>
          <div class="row no-gutter">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no categories added yet. </div>
            </div>
          </div>
        <?php else: ?>
          <?php foreach($categories as $category):?>
            <div class="col-lg-4 col-sm-6 portfolio-item">
                <a href="<?php echo site_url('lessee/shops'); ?>" class="portfolio-box">
                  <img src="http://localhost/rentrmnl/assets/img/portfolio/1.jpg" class="img-responsive" alt="">
                  <h3>
                    <a href="<?php echo site_url('lessee/shops/'); ?>"></a><?php echo $category->category_type;?></a>
                  </h3>
                </a>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>
            </div>
          <?php endforeach;?>
        <?php endif; ?>
        </div>
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
    </div>