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
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Shop Name</th>
                            <th>Shop Branch</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($shops as $shop): ?>
                          <tr>
                              <td><?php echo $shop->shop_name; ?></td>
                              <td><?php echo $shop->shop_branch; ?></td>
                              <td><?php echo $shop->address; ?></td>
                              <td>
                                <div class="btn-group btn-group">
                                  <a href="#" class="btn btn-primary btn-xs">Update</a>
                                  <a href="#" class="btn btn-danger btn-xs">Delete</a>
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