<?php if (!defined('IN_GS')) {
    die('you cannot load this page directly.');
}
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		clq theme for GetSimple CMS
*
*****************************************************/
?>

<?php include('include.php'); ?>

<!-- BODY -->

<body id="<?php get_page_slug(); ?>" class="stickybody clq">
  <div class="container">

    <!-- HEADER -->
    <?php include('header.inc.php'); ?>

    <!-- SITE CONTENT -->
    <h2 class="title"><?php get_page_title(); ?></h2>
    <nav class="sidenav">
      <ul class="sidemenu root">
        <?php get_i18n_navigation(return_page_slug(), 1, 1, I18N_SHOW_MENU, "navbar"); ?>
      </ul>
    </nav>
    <?php  ?>
    <div class="content">
      <div class="page-text">
        <?php get_page_content(); ?>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <?php include('footer.inc.php'); ?>

  <!-- SCRIPTS-->
  <!-- JavaScript -->
  <script type="text/javascript" src="<?php get_theme_url(); ?>/js/doubletaptogo.js"></script>
  <script>
    $(function() {
      $('#navmenu li:has(ul)').doubleTapToGo();
    });
  </script>

</body>

</html>
