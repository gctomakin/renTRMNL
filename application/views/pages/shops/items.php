<?php if (empty($items)) { ?>
  <div class="alert alert-danger"><h2>No Item yet.</h2></div>
<?php } else { ?>
  <?php foreach ($items as $item) { ?>
  <div class="col-sm-3 col-lg-3 col-md-3">
    <div class="thumbnail">
      <img src="<?php echo $item['item_pic']; ?>" alt="" style="width:320px; height: 150px;">
      <div class="caption">
        <div class="btn-group inline pull-right" data-toggle="buttons-checkbox">
          <a data-window='external' href="<?php echo site_url('lessee/items' . '?id=' . $item['item_id']) ?>" class="btn btn-xs btn-primary">View Item</a>
        </div>
        <h4><a href="#"><?php echo empty($item['item_name']) ? 'No Name' : $item['item_name']; ?></a></h4>
        <p><?php echo $item['item_desc']; ?></p>
      </div>
    </div>
  </div>
  <?php } ?>
<?php } ?>