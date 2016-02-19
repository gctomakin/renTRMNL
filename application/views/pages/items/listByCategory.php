<div class="container-fluid">
  <div class="row">
    <?php if (empty($shops)) { ?>
    <div class="alert alert-danger">
      No Shop and item found for this category.
    </div>
    <?php } else { ?>
      <?php foreach($shops as $shop):?>
      <div class="col-sm-3 col-lg-3 col-md-3">
        <div class="thumbnail">
          <img src="<?php echo $shop['detail']['shop_image']; ?>" alt="" style="width:320px; height:150px;">
          <div class="caption">
            <h4>
              <a href="#">
                <?php echo $shop['detail']['shop_name'] . ' - ' . $shop['detail']['shop_name']; ?>
              </a>
            </h4>
            <p><?php echo $shop['detail']['address']; ?></p>
            <table class="table table-small">
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
        </div>
      </div>
      <?php endforeach; ?>
      <!--pagination-->
      <div class="text-center col-lg-12">
        <?php echo empty($pagination) ? '' : $pagination; ?>
      </div>
    <?php } ?>
  </div>
</div>