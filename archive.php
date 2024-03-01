<?php
/*
=================================================================
Filename: archive.php
Description: post list
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

get_header(); ?>
			
<div class="content">	
	<div class="uk-container">
		
		<div class="" uk-grid>
			<div class="uk-width-expand">
				
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			 
					<?php // get_template_part( 'parts/loop', 'blog' ); ?>
					<?php  get_template_part( 'parts/loop', 'blog-grid' ); ?>
						
				<?php endwhile; ?>	

				<?php  wus_page_navi(); ?>
					
				<?php else : ?>
				
					<?php  get_template_part( 'parts/content', 'missing' ); ?>
						
				<?php endif; ?>
				
			</div>
				
			<div class="uk-width-1-6">
				<h3>Sidebar</h3>
				<?php get_sidebar(); ?>
			</div>
		
		</div>

	</div> 
</div> 

<?php get_footer(); ?>