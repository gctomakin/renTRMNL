<div class="container-fluid">
  <div class="row">
    <?php if (empty($shops)) { ?>
    <div class="alert alert-danger">
      No Shop and item found for this category.
    </div>
    <?php } else { ?>
      <?php foreach($shops as $shop):?>
      <div class="col-md-5">
        <a href="#">
          <img class="thumbnail img-responsive" src="<?php echo $shop['detail']['shop_image']; ?>" alt="" style="width:100%; height: 250px;">
        </a>
      </div>
      <div class="col-md-7">
        <h3><?php echo $shop['detail']['shop_name'] . ' - ' . $shop['detail']['shop_branch']; ?></h3>
        <h4><?php echo $shop['detail']['address']; ?></h4>
        <p><?php echo $shop['detail']['shop_desc']; ?></p>
        <table class="table">
          <tr>
            <th>ITEM</th>
            <th>QTY</th>
            <th class='text-right'>PRICE</th>
          </tr>
          <?php foreach ($shop['items'] as $item) { ?>
          <tr>
            <td>
              <a target="_blank" href="<?php echo site_url('/lessee/items?id=' . $item['item_id']); ?>">
                <?php echo $item['item_name']; ?>
                <small>(<?php echo $item['item_desc'] ?>)</small>
              </a>
            </td>
            <td><?php echo number_format($item['item_qty']); ?></td>
            <td class='text-right'><?php echo number_format($item['item_rate'], 2); ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <div class="col-md-12"><hr></div>
      <?php endforeach; ?>
      <!--pagination-->
      <div class="text-center col-lg-12">
        <?php echo empty($pagination) ? '' : $pagination; ?>
      </div>
    <?php } ?>
  </div>
</div>