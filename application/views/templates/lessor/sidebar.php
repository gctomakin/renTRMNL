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
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="panels-wells.html">Panels and Wells</a>
                        </li>
                        <li>
                            <a href="buttons.html">Buttons</a>
                        </li>
                        <li>
                            <a href="notifications.html">Notifications</a>
                        </li>
                        <li>
                            <a href="typography.html">Typography</a>
                        </li>
                        <li>
                            <a href="icons.html"> Icons</a>
                        </li>
                        <li>
                            <a href="grid.html">Grid</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="#">Second Level Item</a>
                        </li>
                        <li>
                            <a href="#">Second Level Item</a>
                        </li>
                        <li>
                            <a href="#">Third Level <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li>
                                    <a href="#">Third Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level Item</a>
                                </li>
                            </ul>
                            <!-- /.nav-third-level -->
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="blank.html">Blank Page</a>
                        </li>
                        <li>
                            <a href="login.html">Login Page</a>
                        </li>
                    </ul>
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