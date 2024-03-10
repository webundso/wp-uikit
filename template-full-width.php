<?php
/*
=================================================================
Template Name: Full width
Filename: template-full-width.php
Description: Full width template
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

get_header(); ?>
			
<div class="content">	
	<div class="uk-container">
		
		<h1 class="page-title"><?php the_title(); ?></h1>
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<?php the_content(); ?>
			
		<?php endwhile; endif; ?>							

	</div>	
</div> 

<?php get_footer(); ?>
