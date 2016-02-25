<header id="particles-js">
    <div class="header-content">
        <div class="header-content-inner">
        <?php if($this->session->flashdata('error_login')) { ?>
            <div class="alert alert-danger">
                <h2>Username and Password does not match.</h2>
            </div>
        <?php } ?>
            <h1>A Software-as-a-Service for Rental Shops</h1>
            <hr>
            <p>renTRMNL facilitates the rental management process and transactions. Design to help users to find the nearest rental shops and to increase the effectiveness and efficiency of renting services. </p>
            <a href="<?php echo site_url('/lessor/signup'); ?>" class="btn btn-primary btn-xl">Become a Lessor</a>
            <a href="#signup" class="btn btn-primary btn-xl page-scroll">Become a Lessee</a>
        </div>
    </div>
</header>

<section class="bg-primary" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h2 class="section-heading">We've got what you need!</h2>
                <hr class="light">
                <p class="text-faded">renTRMNL  provides web and mobile platforms that allow the rental shop owners, freelance lessors, and lessee to perform rental-related transactions. </p>
                <a href="#signup" class="btn btn-default btn-xl page-scroll">Get Started!</a>
            </div>
        </div>
    </div>
</section>

<section id="services">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">At Your Service</h2>
                <hr class="primary">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-diamond wow bounceIn text-primary"></i>
                    <h3>Sturdy Templates</h3>
                    <p class="text-muted">Our templates are updated regularly so they don't break.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-paper-plane wow bounceIn text-primary" data-wow-delay=".1s"></i>
                    <h3>Ready to Ship</h3>
                    <p class="text-muted">You can use this theme as is, or you can make changes!</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-newspaper-o wow bounceIn text-primary" data-wow-delay=".2s"></i>
                    <h3>Up to Date</h3>
                    <p class="text-muted">We update dependencies to keep things fresh.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-heart wow bounceIn text-primary" data-wow-delay=".3s"></i>
                    <h3>Made with Love</h3>
                    <p class="text-muted">You have to make your websites with love these days!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="no-padding" id="portfolio">
    <div class="container-fluid">
        <div class="row no-gutter">
            <?php
                foreach ($categories as $cat) {
                    $img = $cat->category_image == NULL ? site_url('assets/img/portfolio/1.jpg') : 'data:image/jpeg;base64,'.base64_encode($cat->category_image);
            ?>
            <div class="col-lg-4 col-sm-6">
                <a href="javascript:;" class="portfolio-box" data-category-name="<?php echo $cat->category_type; ?>" data-category-id="<?php echo $cat->category_id; ?>">
                <img src="<?php echo $img;?>" class="img-responsive" alt="" style="width:430px; height:230px;">
                    <div class="portfolio-box-caption">
                        <div class="portfolio-box-caption-content">
                            <div class="project-category text-faded">
                                Category
                            </div>
                            <div class="project-name">
                                <?php echo $cat->category_type; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>  
        </div>
    </div>
</section>

<aside class="bg-dark">
    <div class="container text-center">
        <div class="call-to-action">
            <h2>Free Download at renTRMNL!</h2>
            <a href="#" class="btn btn-default btn-xl wow tada">Download Now!</a>
        </div>
    </div>
</aside>

<section class="bg-primary" id="signup">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h2 class="section-heading">Sign Up</h2>
                <hr class="light">
                <?php if($this->session->flashdata('error')) { echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>'; }?>
                <?php if($this->session->flashdata('success')){ echo '<div class="alert alert-success"><strong>Successfully</strong> Signed Up!</div>';} ?>
                <form method="POST" action="<?php echo site_url('accounts/signup'); ?>" id="lessee-form">
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
                    <label for="email">Email address:</label>
                    <input type="email" name="email" class="form-control" id="email">
                  </div>
                  <div class="form-group">
                    <label for="phoneno">Phone no:</label>
                    <input type="number" name="phoneno" class="form-control" id="phoneno">
                  </div>
                  <div class="form-group">
                      <!-- <label for="usertype" class="control-label col-lg-12">Type of User:</label> -->
                      <!-- <div class="form-group text-center">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default active">
                                    Lessee
                                    <input type="radio" name="user_type" value="lessee" checked />
                                </label>
                                <label class="btn btn-default">
                                    Lessor
                                    <input type="radio" name="user_type" value="lessor" />
                                </label>
                            </div>
                        </div> -->
                    <input type="hidden" name="user_type" value="lessee" />
                  </div>
                  <button type="submit" class="btn btn-default btn-xl wow bounce">Sign up</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h2 class="section-heading">Let's Get In Touch!</h2>
                <hr class="primary">
                <p>Ready to start your next project with us? That's great! Give us a call or send us an email and we will get back to you as soon as possible!</p>
            </div>
            <div class="col-lg-4 col-lg-offset-2 text-center">
                <i class="fa fa-phone fa-3x wow bounceIn"></i>
                <p>123-456-6789</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fa fa-envelope-o fa-3x wow bounceIn" data-wow-delay=".1s"></i>
                <p><a href="mailto:your-email@your-domain.com">feedback@startbootstrap.com</a></p>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="text-center login-title">Sign in to continue to renTRMNL</h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php $attributes = array('class' => 'form-signin', 'id' => 'form-signin'); echo form_open('accounts/signin', $attributes); ?>
                            <!-- <div class="form-group text-center">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary btn-signin-type active">
                                        Lessee
                                        <input type="radio" name="usertype" value="lessee" checked />
                                    </label>
                                    <label class="btn btn-primary btn-signin-type">
                                        Lessor
                                        <input type="radio" name="usertype" value="lessor" />
                                    </label>
                                </div>
                            </div> -->
                            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                            <p class="terms-privacy text-center"><small>You agree to the renTRMNL <a href="<?php echo site_url("terms"); ?>" target="_blank">Terms of Service</a> and <a href="<?php echo site_url("privacy"); ?>" target="_blank">Privacy Policy</a>.</small></p>
<!--                             <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
 -->                        <?php echo form_close(); ?>
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
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-fullscreen fade" id="category-modal" tabindex='-1'>
  <div class="modal-dialog">
    <div class="modal-content" style="background: #fff!important; margin-top:2rem;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">ITEM CATEGORY <strong id="category-modal-title"></strong></h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
    var signinLesseeUrl = "<?php echo site_url('lessees/signin'); ?>";
    var signinLessorUrl = "<?php echo site_url('lessors/signin'); ?>";
    var categoryItemsUrl = "<?php echo site_url('main/listByCategory'); ?>";
</script>