<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		wow-css theme for GetSimple CMS
*
*****************************************************/
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Do you love Bootstrap's grid but waste a lot of time modifying everything else?">
<meta name="author" content="">
<link rel="icon" href="<?php get_theme_url(); ?>/images/favicon.ico">
<title>
<?php if (function_exists('get_custom_title_tag'))
		{echo(get_custom_title_tag());}
		else { get_page_clean_title(); echo"&nbsp;&mdash;&nbsp;"; get_site_name(); }  ?>
</title>
<?php get_header(); ?>
<!-- Wow Framework CSS -->
<link href="<?php get_theme_url(); ?>/assets/css/wowframework.css" rel="stylesheet">
<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!-- Custom styles for this template -->
<link href="<?php get_theme_url(); ?>/assets/css/theme.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="<?php get_page_slug(); ?>" class="stickybody">


<!-- HEADER -->
<div class="header-wrapper sticky">
	<header>
	<a href="<?php get_site_url(); ?>" class="logo"><?php get_site_name(); ?></a>
	<nav class="nav-collapse">
	<ul>
		<?php if (function_exists('get_i18n_navigation'))
		{echo(get_i18n_navigation(return_page_slug(),0,1,I18N_SHOW_MENU));}
		else { echo '<span class="pajaxNav">';get_navigation(return_page_slug());echo '</span>'; }  ?>

	</ul>
	</nav>
	</header>
</div>
<!-- HEADER -->


<!-- SITE CONTENT -->


<div class="container">

	<div class="row">
		<div id="content" class="c12">
			<h1><?php echo '<span class="pajaxTitle">';get_page_title();echo '</span>'; ?></h1>	
				<div id="page-content">
					<div class="page-text">
						<?php echo '<span class="pajaxContent">';get_page_content();echo '</span>'; ?>
					</div>
				</div>
		</div>
	</div>
	
</div>
<!-- SITE CONTENT -->


<!-- FOOTER -->
<footer class="text-center">
<div class="container">
	<a target="_blank" href="https://twitter.com/wowthemesnet"><i class="fa fa-twitter fa-2x"></i></a> &nbsp; <a target="_blank" href="https://www.facebook.com/pages/wowthemesnet/562560840468823"><i class="fa fa-facebook fa-2x"></i></a> &nbsp; <a target="_blank" href="https://plus.google.com/b/110916582192388695332/+WowthemesNet/posts"><i class="fa fa-google-plus fa-2x"></i></a>
	<hr>
	 &copy; <?php echo date('Y'); ?> - <strong><?php get_site_name(); ?></strong>. <br/>
	<i>Wow CSS Framework</i> uses MIT license.<br/>
	<?php get_site_credits(); ?>
</div>
</footer>
<!-- FOOTER -->

<?php get_footer(); ?>

<!-- SCRIPTS-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/plugins.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/custom.js"></script>
<!-- SCRIPTS -->


</body>
</html>
