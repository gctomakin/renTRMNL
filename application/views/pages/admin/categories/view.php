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
                <table id="category-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Category Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $category): ?>
                          <tr>
                              <td><?php echo $category->category_id;?></td>
                              <td>
                              <?php
                                $itemPic = $category->category_image == NULL ?
                                'http://placehold.it/150x100' :
                                'data:image/jpeg;base64,'.base64_encode($category->category_image);
                              ?>
                              <img src="<?php echo $itemPic; ?>" alt="category image" style="width: 150px; height:150px;">
                              </td>
                              <td><?php echo $category->category_type;?></td>
                              <td>
                                <div class="btn-group btn-group">
                                  <a href="<?php echo site_url('admin/categories/edit/' . $category->category_id); ?>" class="btn btn-primary btn-xs">Update</a>
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