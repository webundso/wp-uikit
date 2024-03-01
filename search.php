<?php
/*
=================================================================
Filename: search.php
Description: search & result page
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/
 	
get_header(); ?>
			
<div class="content">	
	<div class="uk-container">
					
		<h1 class="archive-title"><?php _e( 'Suchergebnisse für:', 'webundso_wp' ); ?> <?php echo esc_attr(get_search_query()); ?></h1>
			
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			 
			<?php get_template_part( 'parts/loop', 'archive' ); ?>
				    
		<?php endwhile; ?>	

		<?php wus_page_navi(); ?>
					
		<?php else : ?>
				
			<?php get_template_part( 'parts/content', 'missing' ); ?>
						
		 <?php endif; ?>
		
	</div>
</div>

<?php get_footer(); ?>
