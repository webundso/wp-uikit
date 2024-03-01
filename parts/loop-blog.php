<?php
/*
=================================================================
Filename: loop-blog.php
Description: Displays posts as list
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/
?>

<section class="uk-section" id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>					
	<div class="uk-container">
		
		<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		<div class="uk-grid" uk-grid>
			
			<div class="uk-width-1-4">
				<div class="uk-background-cover uk-height-medium" data-src="<?php echo the_post_thumbnail_url('large') ?>" uk-img>
					<a class="uk-display-block" href="<?php the_permalink() ?>"></a>			
				</div>
			</div>
			<div class="uk-width-expand">
				<div class="byline">
					Erstellt von <?php the_author_posts_link(); ?>  am <?php the_time('j. F Y') ?>  - <?php the_category(', ') ?>
				</div>
		
				<?php the_content('<button class="uk-button uk-button-default">' . __( 'Weiter lesen', 'webundso_wp' ) . '</button>'); ?>
						
				<div class="article-footer">
    				<p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'wustheme') . '</span> ', ', ', ''); ?></p>
				</div> 
			</div>
			
		</div>
	
	</div>			    						
</section> 