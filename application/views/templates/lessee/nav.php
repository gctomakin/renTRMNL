<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top navbar-lessee" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo site_url('lessees');?>">
            <img src="<?php echo site_url('assets/img/rentrmnllogo.png'); ?>" alt="" style="width: 100%; height: 200%; margin-top: -10px;">
        </a>
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
                <i class="fa fa-bell fa-fw"></i><span class="badge nav-badge" style="display: none;">0</span>  <i class="fa fa-caret-down"></i>
            </a>
            <ul id="top-notification-list" class="dropdown-menu dropdown-alerts">
                
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
                 <span><strong><?php echo $this->session->userdata('lessee_fname'); ?></strong></span> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="<?php echo site_url('lessee/profile'); ?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="<?php echo site_url('logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>