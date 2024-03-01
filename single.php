<?php
/*
=================================================================
Filename: single.php
Description: Post detail page
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
			
		    	<article class="uk-article" id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
						<h1 class="uk-article-title"><?php the_title(); ?></h1>
						<div class="uk-article-meta">
							Erstellt von <?php the_author_posts_link(); ?>  am <?php the_time('j. F Y') ?>  - <?php the_category(', ') ?>
						</div>

						<div class="uk-background-center-center uk-background-cover uk-height-medium" style="background-image: url(<?php echo the_post_thumbnail_url('full') ?>)"></div>

						<?php the_content(); ?>

						<div class="article-footer">
							<?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'webundso_wp' ), 'after'  => '</div>' ) ); ?>
							<p class="tags"><?php the_tags('<span class="tags-title">' . __( 'Tags:', 'webundso_wp' ) . '</span> ', ', ', ''); ?></p>	
						</div> <!-- end article footer -->
											
						<?php comments_template(); ?>	
																		
					</article> <!-- end article -->
		    		
		    <?php endwhile; else : ?>
			
		   		<?php get_template_part( 'parts/content', 'missing' ); ?>
	
		  	<?php endif; ?>
			
			</div>
			
			<div class="uk-width-1-4">
				
				<h3>Sidebar</h3>	
				<?php get_sidebar(); ?>
				
			</div>	
			
		</div>
	</div>
</div> 

<?php get_footer(); ?>