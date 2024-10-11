<?php
/*
=================================================================
Filename: footer.php
Description: Page footer
Author: Noël Girstmair | webundso GmbH
Last changes: 20.6.2024
=================================================================
*/
?>
					
<footer class="footer uk-margin-medium-top">
	
	<div class="uk-container">		
		<div class="uk-text-left uk-child-width-1-3@s" uk-grid>
			
			<div class="uk-panel">
				<h3>Footer Menu </h3>
				<?php  wus_footernav('footer-nav'); ?>
			</div>
			
			<div class="uk-panel">
					Spalte 2
			</div>
			
			<div class="uk-panel">
				Spalte 3
			</div>
			
		</div>
	</div>
	
	<div class="copy uk-container uk-margin-medium-top	">
		&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
	</div>
	
</footer> 
		
<!-- to top -->
<a href="#" class="footer-to-top" uk-scroll><span uk-icon="icon: chevron-up; ratio: 2"></span></a>		
<?php wp_footer(); ?>
		
	
	</body>
</html> 