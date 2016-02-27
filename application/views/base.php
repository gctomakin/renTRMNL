<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
	<title>renTRMNL - <?php echo empty($title) ? 'a Software-as-a-Service for Rental Shops' : $title; ?></title>

	<!-- DEFAULT STYLES -->
  <?php if (empty($hasNewBootrstap)) { ?>
	<!-- Bootstrap Core CSS -->
  <link rel="stylesheet" href="<?php echo site_url("bower_components/bootstrap/dist/css/bootstrap.min.css"); ?>" type="text/css">
  <?php } ?>
  <!-- Custom Fonts -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php echo site_url("assets/font-awesome/css/font-awesome.min.css"); ?>" type="text/css">

  <!-- Custom Theme CSS -->
  <link rel="stylesheet" href="<?php echo site_url("assets/css/style.css"); ?>" type="text/css">

	<!-- ADDITIONAL STYLES -->
	<?php
		if (!empty($style) && is_array($style)) {
      $css = "";
      foreach ($style as $key => $value) {
        $css .= "<link rel='stylesheet' href='". site_url('assets/css') ."/$value.css'>\n";
      }
      echo $css;
    }
	?>
  
  <!-- jQuery -->
  <script src="<?php echo site_url("bower_components/jquery/dist/jquery.min.js"); ?>"></script>

</head>
<body <?php echo empty($bodyId) ? '' : "id='$bodyId'" ; ?> >
  <?php if($this->session->has_userdata('lessee_id')):?>
  <input type="hidden" id="sessionId" value="<?php echo $this->session->has_userdata('lessee_id'); ?>"/>
  <input type="hidden" id="userType" value="lessee"/>
  <?php elseif($this->session->has_userdata('lessor_id')):?>
  <input type="hidden" id="sessionId" value="<?php echo $this->session->userdata('lessor_id'); ?>"/>
  <input type="hidden" id="userType" value="lessor"/>
  <?php elseif($this->session->has_userdata('admin_id')): ?>
  <input type="hidden" id="sessionId" value="<?php echo $this->session->userdata('admin_id'); ?>"/>
  <input type="hidden" id="userType" value="admin"/>
  <?php endif; ?>
	<?php echo empty($content) ? '' : $content; ?>

  <!-- COMMON TEMPLATE -->
  <script type="text/template" id="top-notify-template">
    <li>
      <a href="<%= link %>">
        <div>
          <i class="fa fa-exclamation fa-fw"></i> <%= notification %> 
          <br><small> from - <%= sender %></small>
          <span class="pull-right text-muted small"><%= date %></span>
        </div>
      </a>
    </li>
    <li class="divider"></li>
  </script>
  <script type="text/template" id="top-message-notify-template">
    <li>
      <a href="<%=link%>">
        <div>
          <strong><%=name%></strong>
          <span class="pull-right text-muted">
              <em><%=date%></em>
          </span>
        </div>
        <div><%=message%></div>
      </a>
    </li>
    <li class="divider"></li>
  </script>
  <script type="text/template" id="notify-template">
    <a href="#" class="list-group-item notify-msg-show">
      <i class="fa fa-comment fa-fw"></i> <%= subject %>
      <span class="pull-right text-muted small"><small><%= date %></small></span>
    </a>
    <p class="notify-msg" hidden><%= message %></p>
  </script>
  <script type="text/template" id="message-detail-template">
    <div class="col-lg-12" id="">
      <blockquote class='<%= position %>' style='width:100%; display:block; border-bottom: 1px #bbb solid; margin-bottom: 20px;'>
        <div class="col-lg-12">
            <span style="color:#444;"><%= name %></span>
            <a href="javascript:;" style="display: none;">
              <span>
                <i title='Delete Message' data-placement="left"
                   class="fa fa-1x fa-remove <%= position %>">
                </i>
              </span>
            </a> 
        </div>
        <div class="col-lg-2">
        <% if (typeof(image) !== "undefined") { %>
          <img src="<%=image%>" class="thumbnail" data-toggle="popover" data-placement="right" title="<%= name %>" style="width:140px; height:100px;"><br/>
        <% } %>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
          <p style='word-wrap: break-word; height: 70px;' height='70px' class='dotdotdotWrapper'>
            <%= message %>
          </p>
          <span style='font-size:10px;'>
          <small><%= date %></small>
          </span>
        </div>
      </blockquote>
    </div>
  </script>
  <!-- DEFAULT SCRIPTS -->
  <!-- Bootstrap Core JavaScript -->
  <script src="<?php echo site_url("bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
  <!-- Misc Libs -->
  <script src="<?php echo site_url("bower_components/underscore/underscore-min.js"); ?>"></script>
  <script src="<?php echo site_url("bower_components/pusher/dist/pusher.min.js"); ?>"></script>
  <script src="<?php echo site_url("assets/js/app2.js"); ?>"></script>

	<!-- ADDITIONAL SCRIPTS -->
	<?php
		if (!empty($script)) {
      /** Additional Scripts */
      $js = "";
      foreach ($script as $key => $value) {
        $js .= "<script src='". site_url('assets/js') ."/$value.js'></script>\n";
      }
      echo $js;
    }
	?>
</body>
</html>