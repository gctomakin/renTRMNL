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
                    <a href="<?php echo site_url("lessees");?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo site_url("lessee/myinterests");?>"><i class="fa fa-table fa-fw"></i> My Interests</a>
                </li>
                <li>
                    <a href="<?php echo site_url("lessee/myshops");?>"><i class="fa fa-edit fa-fw"></i> My Shops</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-wrench fa-fw"></i> Items<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="panels-wells.html">Rent</a>
                        </li>
                        <li>
                            <a href="buttons.html">Reserved</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="<?php echo site_url("lessee/inbox");?>"><i class="fa fa-dashboard fa-fw"></i> Inbox</a>
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
        </div>
        <!-- /.sidebar-collapse -->
    </div>
</nav>