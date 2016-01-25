<div class="container-fluid">
  <div class="row no-gutter">
    <div class="col-md-offset-2 col-md-8 col-md-offset-2 well">
      <?php if($this->session->flashdata('error')) { echo '<div class="alert alert-danger text-center">'.$this->session->flashdata('error').'</div>'; }?>
      <?php if($this->session->flashdata('success')){ echo '<div class="alert alert-success text-center"><strong>Successfully</strong> Saved Category!</div>';} ?>
      <?php echo form_open($action, array('enctype' => "multipart/form-data")); ?>
      <?php
        $type = empty($category['category_type']) ? '' : $category['category_type'];
        $image = empty($category['category_image']) || $category['category_image'] == NULL ? 'http://placehold.it/700x300' : 'data:image/jpeg;base64,'.base64_encode($category['category_image']);
      ?>
        <div class="form-group">
          <label for="category">Category:</label>
          <input type="text" name="category" value="<?php echo $type; ?>" class="form-control" id="category">
        </div>
        <div class="form-group">
          <label for="image">Image:</label>
          <input type="file" name="image" id="image" accept="image/*" value="<?php echo $image; ?>">
          <img src="<?php echo $image; ?>" id="preview-image" alt="" class="thumbnail" style="width:100%; height: auto;"> 
          <input type="hidden" name="id" value="<?php echo empty($category['category_id']) ? '' : $category['category_id']; ?>">
        </div>
        <button type="submit" class="btn btn-default btn-xl wow bounce">Submit</button>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
