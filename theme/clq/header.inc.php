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

<div class="header-wrapper banner">
		<header>
			<a href="<?php get_site_url(); ?>" class="logo"><?php get_site_name(); ?></a>
		</header>
		<div class="tools">
			<div class="contact-band">
				<span>
					<a href="">
						<i class="fa fa-phone"></i>(450) 788-2680
					</a>
				</span>
				<span>
					<a href="mailto:info@campinglequebecois.qc.ca">
						<i class="fa fa-envelope"></i>info@campinglequebecois.qc.ca
					</a>
				</span>
			</div>
      <div class="lang">
        <?php
        $languages = return_i18n_languages();
        if(count($languages) > 1){
          $other_lang = $languages[1];
        } else {
          $other_lang = $languages[0];
        }

        $txt = "English";
        if ($other_lang != "en"){
          $txt = "Fran&ccedil;ais";
        }
         ?>
         <a href="<?php echo return_i18n_setlang_url($other_lang); ?>"><?php echo $txt; ?> </a>
      </div>
	</div>
	<!-- NAVIGATION BAR -->
	<nav  class="topnav hw-auto center right upper">
		<label for="show-menu" class="show-menu">Menu</label>
		<input id="show-menu" type="checkbox"  role="button">
		<ul id="navmenu" class="rootnav">
			<?php get_i18n_navigation(return_page_slug(), 0, 1, I18N_SHOW_MENU, 'navbar'); ?>
		</ul>
	</nav>
</div>
