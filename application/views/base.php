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
	<!-- Bootstrap Core CSS -->
  <link rel="stylesheet" href="<?php echo site_url("bower_components/bootstrap/dist/css/bootstrap.min.css"); ?>" type="text/css">
  <!-- Custom Fonts -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php echo site_url("assets/font-awesome/css/font-awesome.min.css"); ?>" type="text/css">
  <!-- Plugin CSS -->
  <link rel="stylesheet" href="<?php echo site_url("assets/css/animate.min.css"); ?>" type="text/css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo site_url("assets/css/creative.css"); ?>" type="text/css">
	
	<!-- ADDITIONAL STYLES -->
	<?php
		if (!empty($styles)) { echo $styles; }
	?>
</head>
<body <?php echo empty($bodyId) ? '' : "id='$bodyId'" ; ?> >
	
	<?php echo empty($content) ? '' : $content; ?>	


	<!-- DEFAULT SCRIPTS -->
  <!-- jQuery -->
  <script src="<?php echo site_url("bower_components/jquery/dist/jquery.min.js"); ?>"></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="<?php echo site_url("bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
  <!-- Plugin JavaScript -->
  <script src="<?php echo site_url("assets/js/jquery.easing.min.js"); ?>"></script>
  <script src="<?php echo site_url("assets/js/jquery.fittext.js"); ?>"></script>
  <script src="<?php echo site_url("assets/js/wow.min.js"); ?>"></script>
  <!-- Custom Theme JavaScript -->
  <script src="<?php echo site_url("assets/js/creative.js"); ?>"></script>

	<!-- ADDITIONAL SCRIPTS -->
	<?php
		if (!empty($scripts)) { echo $scripts; }
	?>
</body>
</html>