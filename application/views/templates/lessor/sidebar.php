    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                    </div>
                    <!-- /input-group -->
                </li>
                <li>
                    <a href="<?php echo site_url('lessor/dashboard'); ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#shops"><i class="fa fa-building fa-fw"></i> Shops<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo site_url('lessor/shops/create'); ?>">Create</a></li>
                        <li><a href="<?php echo site_url('lessor/shops/list'); ?>">List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#item-nav"><i class="fa fa-cubes fa-fw"></i> Items<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo site_url('lessor/items/create'); ?>">Create</a></li>
                        <li><a href="<?php echo site_url('lessor/items/list'); ?>">List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#rent-nav"><i class="fa fa-tag fa-fw"></i> Rental<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="#">Rented Items</a></li>
                        <li><a href="#">Pending Rentals</a></li>
                        <li><a href="#">History</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo site_url('lessor/subscriptions') ?>"><i class="fa fa-info fa-fw"></i> Subscriptions</a>
                </li>
                <li>
                    <!-- /.nav-second-level -->
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
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
</nav>