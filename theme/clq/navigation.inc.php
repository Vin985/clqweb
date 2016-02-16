<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		clq theme for GetSimple CMS
*
*****************************************************/
?>
<div class="nav-wrapper">
	<nav class="nav-collapse" >
			<ul>
				<span class="pajaxNav">
					<?php function_exists('get_i18n_navigation') ? get_i18n_navigation(return_page_slug(), 0, 1, I18N_SHOW_MENU) : get_navigation(return_page_slug()); ?>				
				</span>
			</ul>
	</nav>
	

</div>