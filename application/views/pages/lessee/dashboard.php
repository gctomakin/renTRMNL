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
          <?php $img = $category->category_image == NULL ? site_url('assets/img/portfolio/1.jpg') : 'data:image/jpeg;base64,'.base64_encode($category->category_image); ?>
        
            <a href="<?php echo site_url('lessee/shops/category/' . $category->category_id); ?>" class="portfolio-box">
              <img src="<?php echo $img; ?>" class="img-responsive" alt="" style="width:430px; height: 230px;">
              <h3><?php echo $category->category_type;?></a></h3>
            </a>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae.</p>
        </div>
      <?php endforeach;?>
    <?php endif; ?>
    </div>
    <!--pagination-->
    <div class="row text-center">
      <div class="col-lg-12">
        <?php
          if (!empty($pagination)) {
            echo $pagination;
          }
        ?>
      </div>
  </div>
</div>