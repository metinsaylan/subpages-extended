<?php

/* Inserts automatic subpages indexes on empty pages */
add_filter('the_content', 'shailan_subpages_filter');
function shailan_subpages_filter($content){
	global $post;

	/* If there is content, return */
	if( strlen($content) != 0 )
		return $content;

	/* Check auto-insert option */
	$autoInsertEnabled = (bool) get_option( 'subpages_extended_auto_insert');
	if( ! $autoInsertEnabled )
		return $content;

	$parent = $post->ID;
	$children = get_pages( array( 'child_of' => $parent ) );
	$depth = 4;
	$exclude = '';

	if ( ! empty( $children ) ) {
		ob_start();
		?>
		<div class="shailan-subpages-container">
				<ul class="subpages">
					<?php wp_list_pages('sort_column=menu_order,post_title&depth='.$depth.'&title_li=&child_of='.$post->ID.'&exclude='.$exclude); ?>
				</ul>
		</div>
		<?php
		$subpages = ob_get_clean();
		return $subpages;
	} else {
		return $content . "\n\t<!-- SUBPAGES : This page doesn't have any subpages. -->";
	}
}


