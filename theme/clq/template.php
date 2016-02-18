<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		clq theme for GetSimple CMS
*
*****************************************************/

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="camping le quebecois">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<link rel="icon" href="<?php get_theme_url(); ?>/images/favicon.ico">

		<title>
			<?php if (function_exists('get_custom_title_tag')){
					echo(get_custom_title_tag());
				} else {
					get_page_clean_title(); echo"&nbsp;&mdash;&nbsp;"; get_site_name();
				}  ?>
		</title>

		<!-- JavaScript -->
		<script type="text/javascript" src="<?php get_theme_url(); ?>/js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php get_theme_url(); ?>/js/jquery-migrate-1.2.1.min.js"></script>

		<!-- Fonts
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600,400&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
 	-->

		<!-- CLQ CSS -->
		<link rel="stylesheet" href="<?php get_theme_url(); ?>/css/global.css" type="text/css">
		<link rel="stylesheet" href="<?php get_theme_url(); ?>/css/font-awesome.min.css" type="text/css">
		<!-- Custom styles for this template -->
		<link rel="stylesheet" href="<?php get_theme_url(); ?>/css/clq.css" type="text/css">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->

		<?php function_exists('get_i18n_header') ? get_i18n_header() : get_header(); ?>
</head>

<!-- BODY -->
<body id="<?php get_page_slug(); ?>" class="stickybody">

	<!-- HEADER -->
	<?php include('header.inc.php'); ?>

	<!-- NAVIGATION BAR -->
	<nav  class="topnav">
		<label for="show-menu" class="show-menu">Menu</label>
		<input type="checkbox" id="show-menu" role="button">
		<ul id="topnav">
			<?php get_i18n_navigation(return_page_slug(), 0, 1, I18N_SHOW_MENU); ?>
		</ul>
	</nav>

	<!-- SITE CONTENT -->

	<div class="container">
		<h2 class="title"><?php get_page_title(); ?></h2>
		<?php  ?>
		<nav class="sidenav nav-collapse">
				<ul>
					<?php get_i18n_navigation(return_page_slug(), 1, 1, I18N_SHOW_MENU); ?>
				</ul>
		</nav>
			<?php  ?>
		<div class="content">
				<div class="page-text pajaxContent">
					<?php get_page_content(); ?>
				</div>
			</div>
	</div>
	<!-- SITE CONTENT -->


	<!-- FOOTER -->
	<?php include('footer.inc.php'); ?>


	<!-- SCRIPTS-->
	<!--<script type="text/javascript" src="<?php get_theme_url(); ?>/js/plugins.js"></script>
	<script type="text/javascript" src="<?php get_theme_url(); ?>/js/custom.js"></script>
	<script type="text/javascript" src="<?php get_theme_url(); ?>/js/bootstrap.min.js"></script>-->
	<!--  SCRIPTS -->

</body>
</html>
