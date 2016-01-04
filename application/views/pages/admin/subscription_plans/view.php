<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-1 col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
            Plans
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
          <?php if(empty($plans)):?>
            <div class="col-md-12">
              <div class="alert alert-warning" role="alert"> <strong>Oops!</strong> Better check later, no plans added yet. </div>
            </div>
          <?php else:?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Plan Name</th>
                            <th>Plan Desc</th>
                            <th>Plan Type</th>
                            <th>Plan Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($plans as $plan): ?>
                          <tr>
                              <td><?php echo $plan->plan_name;?></td>
                              <td><?php echo $plan->plan_desc;?></td>
                              <td><?php echo $plan->plan_type;?></td>
                              <td><?php echo $plan->plan_rate;?></td>
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