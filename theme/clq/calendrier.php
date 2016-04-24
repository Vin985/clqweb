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

<body onload="init();" id="<?php get_page_slug(); ?>" class="stickybody clq">
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
        <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
            <div class="dhx_cal_navline">
        <div class="dhx_cal_prev_button">&nbsp;</div>
        <div class="dhx_cal_next_button">&nbsp;</div>
        <div class="dhx_cal_today_button"></div>
        <div class="dhx_cal_date"></div>
        <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
        <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
        <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
    </div>
    <div class="dhx_cal_header"></div>
    <div class="dhx_cal_data"></div>
</div>

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
