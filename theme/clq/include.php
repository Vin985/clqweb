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
				}
				?>
	</title>

	<link href="http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic" rel="stylesheet" type="text/css">

	<!-- CLQ CSS -->
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/css/global.css" type="text/css">
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/css/font-awesome.min.css" type="text/css">
	<!-- Custom styles for this template -->
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/css/clq.css" type="text/css">
	<link rel="stylesheet" href="<?php get_theme_url(); ?>/css/clqless.css" type="text/css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
			  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->

	<!-- JavaScript -->
	<script type="text/javascript" src="<?php get_theme_url(); ?>/js/jquery.min.js" async></script>
	<script type="text/javascript" src="<?php get_theme_url(); ?>/js/jquery-migrate-1.2.1.min.js" defer></script>

	<?php function_exists('get_i18n_header') ? get_i18n_header() : get_header(); ?>
</head>
