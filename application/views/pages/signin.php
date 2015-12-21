
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Sign in to continue to renTRMNL</h1>
            <?php if($this->session->flashdata('error')) { echo '<p class="alert alert-danger text-center"><strong>Login Failed!</strong> Invalid username/password :(</p>'; }?>
            <div class="account-wall">
                <!--<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt=""> -->
                <?php 
                    $attributes = array('class' => 'form-signin', 'id' => 'form-signin');
                    echo form_open($action, $attributes);
                ?>
                <input type="text" name="username" class="form-control" placeholder="Email" required autofocus>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">
                    Sign in</button>
                <label class="checkbox pull-left">
                    <input type="checkbox" value="remember-me">
                    Remember me
                </label>
                <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                <?php echo form_close(); ?>
            </div>
            <a href="<?php echo site_url("/"); ?>" class="text-center new-account"><i class="fa fa-home"></i> Go back to home page </a>
        </div>
    </div>
</div>