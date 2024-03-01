<?php
/*
=================================================================
Filename: loop-blog-grid.php
Description: Displays post-list as grid
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

// Adjust the amount of rows in the grid
$grid_columns = 4; ?>

<?php if( 0 === ( $wp_query->current_post  )  % $grid_columns ): ?>

  <div class="uk-child-width-1-3@m" uk-grid>

<?php endif; ?> 

		<!--Item: -->
		<div>
				<div class="uk-card uk-card-default">
						<div class="uk-card-media-top uk-background-cover uk-height-medium" data-src="<?php echo the_post_thumbnail_url('large') ?>" uk-img>
							<a class="uk-display-block" href="<?php the_permalink() ?>"></a>			
						</div>
						<div class="uk-card-body">
								<h3 class="uk-card-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
								<p>	<?php the_content('<button class="tiny">' . __( 'Read more...', 'webundso_wp' ) . '</button>'); ?> </p>
						</div>
				</div>
		</div>
		

<?php if( 0 === ( $wp_query->current_post + 1 )  % $grid_columns ||  ( $wp_query->current_post + 1 ) ===  $wp_query->post_count ): ?>

  </div>  <!--End Grid --> 

<?php endif; ?>

