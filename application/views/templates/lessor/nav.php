<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top navbar-lessor" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/lessor/dashboard">renTRMNL - LESSOR</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="top-message-notification">
                <i class="fa fa-envelope fa-fw"></i><span class="badge nav-badge" style="display: none;">0</span> <i class="fa fa-caret-down"></i>
            </a>
            <ul id="top-message-notification-list" class="dropdown-menu dropdown-messages">
            </ul>
            <!-- /.dropdown-messages -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="top-notification">
                <i class="fa fa-bell fa-fw"></i><span class="badge nav-badge" style="display: none;">0</span> <i class="fa fa-caret-down"></i>
            </a>
            <ul id="top-notification-list" class="dropdown-menu dropdown-alerts">
                <!-- <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-comment fa-fw"></i> New Comment
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small">12 minutes ago</span>
                        </div>
                    </a>
                </li> -->
            </ul>
            <!-- /.dropdown-alerts -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="pull-left">
                  <img id="img-top" class="media-object" width="25" height="25" src="<?php echo ($this->session->has_userdata('access_token') ? $this->session->userdata('image') : ( is_null($this->session->userdata('image')) ? site_url('assets/img/default.gif') : site_url("uploads/".$this->session->userdata('image')))); ?>" alt="">
                </span>
                &nbsp;
                 <!-- <i class="fa fa-user fa-fw"></i> -->
                 <span><strong><?php echo $this->session->userdata('lessor_fullname'); ?></strong></span> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="<?php echo site_url('lessor/profile/edit') ?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="<?php echo site_url('lessor/account'); ?>"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="<?php echo site_url('logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>