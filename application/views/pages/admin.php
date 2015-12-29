<div class="container admin-container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <?php
        if($this->session->flashdata('error')) {
            echo '<p class="alert alert-danger text-center"><strong>Login Failed!</strong> Invalid username/password :(</p>';
         }
        if($this->session->flashdata('warning')) {
            echo '<p class="alert alert-warning text-center"><strong>Login Failed!</strong> Invalid Usertype :(</p>';
        }
        ?>
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Admin Sign In</h3>
                </div>
                <div class="panel-body">
                    <?php  echo form_open($action); ?>
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username" name="username" type="username" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                </label>
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                        </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <a href="<?php echo site_url("/"); ?>" class="text-center new-account"><i class="fa fa-home"></i> Go back to home page </a>
        </div>
    </div>
</div>