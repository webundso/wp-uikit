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

$wrapper_attributes = get_block_wrapper_attributes(array(
	'class' => 	'block-inner',
	'id'		=>	''
));

?>

<div <?php echo $wrapper_attributes; ?>>
	<div class="uk-container">
		<InnerBlocks />
	</div>
</div>