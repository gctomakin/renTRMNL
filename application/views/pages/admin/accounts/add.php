<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-2 col-md-8 col-md-offset-2 well">
      <?php if($this->session->flashdata('error')) { echo '<div class="alert alert-danger text-center">'.$this->session->flashdata('error').'</div>'; }?>
      <?php if($this->session->flashdata('success')){ echo '<div class="alert alert-success text-center"><strong>Successfully</strong> Added Account!</div>';} ?>
      <?php echo form_open('admin/account/add'); ?>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" name="username" class="form-control" id="username">
        </div>
        <div class="form-group">
          <label for="pwd">Password:</label>
          <input type="password" name="password" class="form-control" id="pwd">
        </div>
        <div class="form-group">
          <label for="pwd2">Confirm Password:</label>
          <input type="password" name="password2" class="form-control" id="pwd2">
        </div>
        <div class="form-group">
          <label for="fname">First Name:</label>
          <input type="text" name="fname" class="form-control" id="fname">
        </div>
        <div class="form-group">
          <label for="lname">Last Name:</label>
          <input type="text" name="lname" class="form-control" id="lname">
        </div>
        <div class="form-group">
          <label for="midinit">Middle Initial</label>
          <input type="text" name="midinit" class="form-control" id="midinit">
        </div>
        <button type="submit" class="btn btn-default btn-xl wow bounce">Submit</button>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>