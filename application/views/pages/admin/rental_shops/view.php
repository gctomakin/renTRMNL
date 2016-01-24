<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
            Rental Shops
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <?php if(empty($shops)):?>
            <div class="col-md-12">
              <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no accounts added yet. </div>
            </div>
          <?php else:?>
            <div class="table-responsive">
                <table id="shop-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>SHOP NAME</th>
                            <th>SHOP BRANCH</th>
                            <th>ADDRESS</th>
                            <th class="text-center">OPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($shops as $shop): ?>
                          <tr data-shop-row="<?php echo $shop->shop_id; ?>">
                              <td><?php echo $shop->shop_name; ?></td>
                              <td><?php echo $shop->shop_branch; ?></td>
                              <td><?php echo $shop->address; ?></td>
                              <td class="text-center">
                                <div class="btn-group btn-group">
                                  <button class="btn btn-primary btn-xs btn-delete" data-shop-id="<?php echo $shop->shop_id; ?>">Delete</button>
                                </div>
                              </td>
                          </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
          <?php endif;?>
        </div>
        <!-- /.panel-body -->
    </div>
    </div>
  </div>
</div>
<script>
  var deleteUrl = "<?php echo site_url('rentalshops/delete'); ?>";
</script>