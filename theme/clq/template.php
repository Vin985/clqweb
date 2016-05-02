<?php if (!defined('IN_GS')) {
    die('you cannot load this page directly.');
}


$sidebar = isset($sidebar) ? $sidebar : true;
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		clq theme for GetSimple CMS
*
*****************************************************/
?>

<?php include('inc/include.php'); ?>

<!-- BODY -->

<body id="<?php get_page_slug(); ?>" class="stickybody clq">
  <div class="container">

    <!-- HEADER -->
    <?php include('inc/header.php'); ?>

    <!-- SITE CONTENT -->
    <h2 class="title"><?php get_page_title(); ?></h2>
    <div class="flex fullwidth">
    <nav class="side sidenav">
      <div class="sidemenu root">
        <?php get_i18n_navigation(return_page_slug(), 1, 1, I18N_SHOW_MENU, "navbar"); ?>
      </div>
    </nav>
    <?php  ?>
    <div class="content">
      <div class="page-text">
        <?php get_page_content(); ?>
      </div>
    </div>
    <?php if ($sidebar) { ?>
        <div class="side">
          sidebar
        </div>
<?php
}
    ?>
  </div>
</div>
  <!-- FOOTER -->

    <?php include('inc/footer.php'); ?>

  <!-- SCRIPTS-->
  <!-- JavaScript -->


</body>

</html>
