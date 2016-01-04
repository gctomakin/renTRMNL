<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
            Categories
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <?php if(empty($categories)):?>
            <div class="col-md-12">
              <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no categories added yet. </div>
            </div>
          <?php else:?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $category): ?>
                          <tr>
                              <td><?php echo $category->category_id;?></td>
                              <td><?php echo $category->category_type;?></td>
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