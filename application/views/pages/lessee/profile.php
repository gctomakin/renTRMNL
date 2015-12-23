<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="page-header">Personal Information</h2>
      <?php
      if($this->session->flashdata('ui_error')) {
          echo '<p class="alert alert-danger text-center"><strong>Error!</strong> Failed to update</p>';
       }
      if($this->session->flashdata('ui_success')) {
          echo '<p class="alert alert-success text-center"><strong>Success!</strong> Personal Information Updated</p>';
      }
      if($this->session->flashdata('ui_val_error')) {
          echo '<div class="alert alert-danger">'.$this->session->flashdata('ui_val_error').'</div>';
      }
      ?>
      <?php echo form_open('lessee/update-info'); ?>
        <div class="form-group">
          <label for="fname">First Name:</label>
          <input type="text" name="fname" class="form-control input-sm" id="fname" value="<?php echo $lessee["lessee_fname"]?>">
        </div>
        <div class="form-group">
          <label for="lname">Last Name:</label>
          <input type="text" name="lname" class="form-control input-sm" id="lname" value="<?php echo $lessee["lessee_lname"]?>">
        </div>
        <div class="form-group">
          <label for="email">Email address:</label>
          <input type="email" name="email" class="form-control input-sm" id="email" value="<?php echo $lessee["lessee_email"]?>">
        </div>
        <div class="form-group">
          <label for="phoneno">Phone no:</label>
          <input type="number" name="phoneno" class="form-control input-sm" id="phoneno" value="<?php echo $lessee["lessee_phoneno"]?>">
        </div>
        <button type="submit" class="btn btn-primary btn-sm wow bounce">Update Info</button>
      <?php echo form_close(); ?>
      <h2 class="page-header">Account Information</h2>
      <?php
      if($this->session->flashdata('ua_error')) {
          echo '<p class="alert alert-danger text-center"><strong>Error!</strong> Failed to update</p>';
       }
      if($this->session->flashdata('ua_success')) {
          echo '<p class="alert alert-success text-center"><strong>Success!</strong> Account Information Updated</p>';
      }
      if($this->session->flashdata('ua_val_error')) { echo '<div class="alert alert-danger">'.$this->session->flashdata('ua_val_error').'</div>';
      }
      if($this->session->flashdata('warning')) { echo '<p class="alert alert-warning"><strong>Error!</strong> Invalid Password</p>';
      }
      ?>
      <?php echo form_open('lessee/update-account'); ?>
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" class="form-control input-sm" id="username" value="<?php echo $lessee["username"]?>">
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" name="password" class="form-control input-sm" id="pwd">
      </div>
      <div class="form-group">
        <label for="pwdn">New Password:</label>
        <input type="password" name="new_password" class="form-control input-sm" id="pwdn">
      </div>
      <div class="form-group">
        <label for="pwd2">Confirm Password:</label>
        <input type="password" name="password2" class="form-control input-sm" id="pwd2">
      </div>
      <button type="submit" class="btn btn-primary btn-sm wow bounce">Update Account</button>
      <?php echo form_close(); ?>
      <br>
    </div>
  </div>
</div>