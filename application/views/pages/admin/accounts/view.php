<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
            Admin Accounts
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <?php if(empty($admins)):?>
            <div class="col-md-12">
              <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no accounts added yet. </div>
            </div>
          <?php else:?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($admins as $admin): ?>
                          <tr>
                              <td><?php echo $admin->username?></td>
                              <td><?php echo $admin->admin_fname.' '.$admin->admin_midint.' '.$admin->admin_lname;?></td>
                              <td><?php echo $admin->admin_status?></td>
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