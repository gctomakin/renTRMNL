<div class="container-fluid">
  <div class="row no-gutter">
    <?php foreach($items as $item):?>
    <div class="col-sm-3 col-lg-3 col-md-3">
      <div class="thumbnail">
        <img src="<?php echo $item['item_pic']; ?>" alt="" style="width:320px; height:150px;">
        <div class="caption">
          <h4><a href="#"><?php echo empty($item['item_name']) ? '<span class="text-danger">No Name</span>' : $item['item_name']; ?></a></h4>
          <p>(<?php echo $item['item_desc']; ?>)</p>
          <p>₱ <?php echo number_format($item['item_rate'], 2); ?> 
            <small>
              <?php echo $item['mode_label']; ?>
            </small>
          </p>
          <?php if (isset($item['shop_name'])) { ?>
          <p><?php echo $item['shop_name'] . ' - ' . $item['shop_branch']; ?></p>
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
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <!--pagination-->
    <div class="text-center col-lg-12">
      <?php echo empty($pagination) ? '' : $pagination; ?>
    </div>
  </div>
</div>