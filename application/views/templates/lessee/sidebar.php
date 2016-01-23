    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <form class="form-inline" action="<?php echo site_url('lessee/items'); ?>">
                        <div class="input-group custom-search-form">
                            <input type="text" name="item" class="form-control" placeholder="Search..." value="<?php echo isset($_GET['item']) ? $_GET['item'] : ''; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <!-- /input-group -->
                </li>
                <li>
                    <a href="<?php echo site_url("lessees");?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo site_url("lessee/myinterests");?>"><i class="fa fa-archive fa-fw"></i> My Interests</a>
                </li>
                <li>
                    <a href="<?php echo site_url("lessee/myshops");?>"><i class="fa fa-building-o fa-fw"></i> My Shops</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-cart-plus fa-fw"></i> Items<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo site_url('lessee/items/'); ?>">List</a>
                        </li>
                        <li>
                            <a href="panels-wells.html">Rent</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('lessee/reserved/') ?>">Reserved</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="<?php echo site_url("lessee/inbox");?>"><i class="fa fa-inbox fa-fw"></i> Inbox</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="blank.html">Blank Page</a>
                        </li>
                        <li>
                            <a href="login.html">Login Page</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            </ul>
            &nbsp;
            <!-- notification -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Notifications Panel
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group" id="notify-list">

                    </div>
                    <!-- /.list-group -->
                    <a href="#" class="btn btn-default btn-block">View All Alerts</a>
                </div>
                <!-- /.panel-body -->
            </div>
            <script type="text/template" id="notify-template">
             <a href="#" class="list-group-item">
                 <i class="fa fa-comment fa-fw"></i> <%= subject %>
                 <span class="pull-right text-muted small"><em><%= date %></em>
                 </span>
             </a>
            </script>
            <!-- notification end -->
        </div>
        <!-- /.sidebar-collapse -->
    </div>
</nav>