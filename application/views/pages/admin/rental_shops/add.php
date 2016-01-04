<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-2 col-md-8 col-md-offset-2 well">
      <?php if($this->session->flashdata('error')) { echo '<div class="alert alert-danger text-center">'.$this->session->flashdata('error').'</div>'; }?>
      <?php if($this->session->flashdata('success')){ echo '<div class="alert alert-success text-center"><strong>Successfully</strong> Added Shop!</div>';} ?>
      <?php echo form_open('admin/rentalshop/add'); ?>
        <div class="form-group">
          <label for="shop_name">Shop Name:</label>
          <input type="text" name="shop_name" class="form-control" id="shop_name">
        </div>
        <div class="form-group">
          <label for="shop_branch">Shop Branch:</label>
          <input type="text" name="shop_branch" class="form-control" id="shop_branch">
        </div>
        <div class="form-group">
          <label for="address">Address:</label>
          <input type="text" name="address" class="form-control" id="address">
        </div>
        <button type="submit" class="btn btn-default btn-xl wow bounce">Submit</button>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>