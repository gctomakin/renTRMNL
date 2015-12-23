<div class="container">
  <div class="row">
    <div class="col-sm-12 col-md-offset-1 col-md-10 col-md-offset-1">
      <h2 class="page-header">Personal Information</h2>
      <?php
      $attributes = array('class' => 'well');
      $attributes2 = array('class' => 'form-inline');
      if($this->session->flashdata('ui_error')) {
          echo '<p class="alert alert-danger text-center"><strong>Error!</strong> Failed to update</p>';
       }
      if($this->session->flashdata('ui_success')) {
          echo '<p class="alert alert-success text-center"><strong>Success!</strong> Personal Information Updated</p>';
      }
      if($this->session->flashdata('ui_val_error')) {
          echo '<div class="alert alert-danger">'.$this->session->flashdata('ui_val_error').'</div>';
      }
      if($this->session->flashdata('upload_success')) {
          echo '<p class="alert alert-success text-center"><strong>Success!</strong> Upload Completed!</p>';
      }
      if($this->session->flashdata('upload_error')) {
          echo '<div class="alert alert-danger">'.$this->session->flashdata('upload_error').'</div>';
      }
      ?>
      <div class="row">
        <div class="col-md-offset-3 col-md-6 col-md-offset-3 text-center">
         <div class="form-group">
           <img class="center-block img-thumbnail" alt="Lessee Thumbnail" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE0MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzE0MHgxNDAKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNTFjZmE3YmFlMyB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1MWNmYTdiYWUzIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjQ0LjA1NDY4NzUiIHk9Ijc0LjUiPjE0MHgxNDA8L3RleHQ+PC9nPjwvZz48L3N2Zz4=">
         </div>
          <div class="form-group">
            <?php echo form_open_multipart('lessees/do_upload', $attributes2);?>
                <div class="form-group">
                  <input type="file" name="userfile" size="20" class="form-control input-sm"/>
                </div>
                <button type="submit" class="btn btn-primary btn-sm img-responsive">Upload Pic</button>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
      <?php echo form_open('lessee/update-info', $attributes); ?>
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
      <?php echo form_open('lessee/update-account',$attributes); ?>
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