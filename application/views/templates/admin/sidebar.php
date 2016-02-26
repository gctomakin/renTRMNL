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
                    <a href="<?php echo site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#shops"><i class="fa fa-users fa-fw"></i> Accounts<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo site_url('admin/accounts/add'); ?>">Add Account</a></li>
                        <li><a href="<?php echo site_url('admin/accounts'); ?>">View Accounts</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#item-nav"><i class="fa fa-credit-card fa-fw"></i> Subscriptions<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo site_url('admin/subscriptions/pending'); ?>">Pending Subscriptions</a></li>
                        <li><a href="<?php echo site_url('admin/subscriptions/add'); ?>">Add Subscription Plan</a></li>
                        <li><a href="<?php echo site_url('admin/subscriptions'); ?>">View Subscription Plan</a></li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#item-nav"><i class="fa fa-building fa-fw"></i> Rental Shops<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo site_url('admin/rentalshops/pending'); ?>">Pendings Rental Shop</a></li>
                        <li><a href="<?php echo site_url('admin/rentalshops'); ?>">View Rental Shop</a></li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#item-nav"><i class="fa fa-list fa-fw"></i> Categories<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo site_url('admin/categories/add'); ?>">Add Category</a></li>
                        <li><a href="<?php echo site_url('admin/categories'); ?>">View Category</a></li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#monitor-nav"><i class="fa fa-bullseye fa-fw"></i> Monitor<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo site_url('admin/monitor/lessee'); ?>">Lessees</a></li>
                        <li><a href="<?php echo site_url('admin/monitor/lessor'); ?>">Lessors</a></li>
                        <li><a href="<?php echo site_url('admin/monitor/items'); ?>">Items</a></li>
                        <li><a href="<?php echo site_url('admin/monitor/shops'); ?>">Shops</a></li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-file-text-o fa-fw"></i> Reports<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?php echo site_url('admin/reports/subscriptions'); ?>">Subscription</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/reports/rentals'); ?>">Rental</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/reports/users'); ?>">Users</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
</nav>