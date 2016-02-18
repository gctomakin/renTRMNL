<div class="container-fluid">
  <div class="row">
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
                <?php echo $item['item_name']; ?>
                <small>(<?php echo $item['item_desc'] ?>)</small>
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
  </div>
</div>