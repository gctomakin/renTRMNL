<div class="container-fluid">
  <div class="row no-gutter">
    <table id="shop-table" class='table'>
      <thead>
        <tr>
          <th>Shop Name</th>
          <th>Branch</th>
          <th>Address</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($shops as $shop) { ?>
        <tr data-shop-id="<?php echo $shop->shop_id; ?>">
          <td><?php echo ucfirst($shop->shop_name); ?></td>
          <td><?php echo ucfirst($shop->shop_branch); ?></td>
          <td><?php echo ucfirst($shop->address); ?></td>
          <td>
            <a class="btn btn-info btn-xs" href="<?php echo site_url('lessor/shops/edit/'. $shop->shop_id); ?>">Edit <span class="fa fa-pencil"></span></a>
            <button class="btn btn-primary btn-xs shop-remove-btn" iid="<?php echo $shop->shop_id; ?>">
              <span class="fa fa-trash"></span> Remove
            </button>
          </td>
        </tr>
        <?php }?>      
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  var removeUrl = "<?php echo site_url('rentalshops/delete'); ?>";
</script>