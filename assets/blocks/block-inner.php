<?php
/*
=================================================================
Filename: block-inner.php
Description: Block 
Client: 
Author: Noël Girstmair | webundso GmbH
Last changes: 14.10.2024
=================================================================
*/

// Create id attribute allowing for custom "anchor" value.
$id = '';
if( !empty($block['anchor']) ) {
		$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'block-inner';
if( !empty($block['className']) ) {
		$className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
		$className .= ' align' . $block['align'];
}

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="uk-container">
		<InnerBlocks />
	</div>
</div>