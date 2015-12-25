
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Sign in to continue to renTRMNL</h1>
            <?php
            if($this->session->flashdata('error')) {
                echo '<p class="alert alert-danger text-center"><strong>Login Failed!</strong> Invalid username/password :(</p>';
             }
            if($this->session->flashdata('warning')) {
                echo '<p class="alert alert-warning text-center"><strong>Login Failed!</strong> Invalid Usertype :(</p>';
            }
            ?>
            <div class="account-wall">
                <!--<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt=""> -->
                <?php
                    $attributes = array('class' => 'form-signin', 'id' => 'form-signin');
                    echo form_open($action, $attributes);
                    $userTypeBtn = array(
                        'lessee' => empty($isLessor) ? 'active' : '',
                        'lessor' => empty($isLessor) ? '' : 'active'
                    );
                    $userTypeInput = array(
                        'lessee' => empty($isLessor) ? 'checked' : '',
                        'lessor' => empty($isLessor) ? '' : 'checked'
                    );
                ?>
                <div class="form-group text-center">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary btn-signin-type <?php echo $userTypeBtn['lessee']; ?>">
                            Lessee
                            <input type="radio" name="usertype" value="lessee" <?php echo $userTypeInput['lessee']; ?> />
                        </label>
                        <label class="btn btn-primary btn-signin-type <?php echo $userTypeBtn['lessor']; ?>">
                            Lessor
                            <input type="radio" name="usertype" value="lessor" <?php echo $userTypeInput['lessor']; ?> />
                        </label>
                    </div>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                <p class="terms-privacy text-center"><small>You agree to the renTRMNL <a href="<?php echo site_url("terms"); ?>" target="_blank">Terms of Service</a> and <a href="<?php echo site_url("privacy"); ?>" target="_blank">Privacy Policy</a>.</small></p>
                <?php echo form_close(); ?>
                <div id="social-media-login">
                   <div class="divider-container text-center">
                      <div class="signin-divider"></div>
                      <div class="divider-text">Or Sign in with</div>
                   </div>
                   <div class="text-center">
                       <a href="<?php echo $authUrl; ?>">
                           <div class="google-button social-button x-small"><i class="fa fa-google"></i> Google</div>
                      </a>
                   </div>
                 </div>
            </div>
            <a href="<?php echo site_url("/"); ?>" class="text-center new-account"><i class="fa fa-home"></i> Go back to home page </a>
        </div>
    </div>
</div>
<script type="text/javascript">
    var signinLesseeUrl = "<?php echo site_url('lessees/signin'); ?>";
    var signinLessorUrl = "<?php echo site_url('lessors/signin'); ?>";
</script>