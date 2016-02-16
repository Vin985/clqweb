<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 		template.php
* @Package:		GetSimple
* @Action:		clq theme for GetSimple CMS
*
*****************************************************/
?>

<!--footer -->
<footer class="footer bg-color-lightblack contrast" >

	<!--.container -->
	<div class="container">
		<div class="row">
		
			<div class="col-sm-6">
				<div class="text-left">&copy; <?php echo date('Y'); ?> - <strong><?php get_site_name(); ?></div>
			</div>
			
		</div>
	</div>
	<!-- /.container -->
	
</footer>
<!-- /footer -->
		


<a  id="gotoTop" class="contrast" href="#"><i class="fa fa-angle-up"></i></a>

<?php get_footer(); ?>
		
