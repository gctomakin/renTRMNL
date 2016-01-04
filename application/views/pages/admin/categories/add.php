<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-2 col-md-8 col-md-offset-2 well">
      <?php if($this->session->flashdata('error')) { echo '<div class="alert alert-danger text-center">'.$this->session->flashdata('error').'</div>'; }?>
      <?php if($this->session->flashdata('success')){ echo '<div class="alert alert-success text-center"><strong>Successfully</strong> Added Category!</div>';} ?>
      <?php echo form_open('admin/category/add'); ?>
        <div class="form-group">
          <label for="category">Category:</label>
          <input type="text" name="category" class="form-control" id="category">
        </div>
        <button type="submit" class="btn btn-default btn-xl wow bounce">Submit</button>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>