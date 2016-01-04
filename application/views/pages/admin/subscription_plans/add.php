<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-2 col-md-8 col-md-offset-2 well">
      <?php if($this->session->flashdata('error')) { echo '<div class="alert alert-danger text-center">'.$this->session->flashdata('error').'</div>'; }?>
      <?php if($this->session->flashdata('success')){ echo '<div class="alert alert-success text-center"><strong>Successfully</strong> Added Subscription Plan!</div>';} ?>
      <?php echo form_open('admin/subscription/add'); ?>
        <div class="form-group">
          <label for="plan_name">Plan Name:</label>
          <input type="text" name="plan_name" class="form-control" id="plan_name">
        </div>
        <div class="form-group">
          <label for="plan_desc">Plan Description:</label>
          <input type="text" name="plan_desc" class="form-control" id="plan_desc">
        </div>
        <div class="form-group">
          <label for="plan_type">Plan Type:</label>
          <input type="text" name="plan_type" class="form-control" id="plan_type">
        </div>
        <div class="form-group">
          <label for="plan_rate">Plan Rate:</label>
          <input type="number" name="plan_rate" class="form-control" id="plan_rate">
        </div>
        <button type="submit" class="btn btn-default btn-xl wow bounce">Submit</button>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>