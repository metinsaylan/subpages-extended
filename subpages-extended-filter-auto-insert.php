<?php

function shailan_subpages_filter($content){
	global $post;

	$autoinsert = ! (bool) get_option( 'subpages_extended_auto_insert');

	if( strlen($content) != 0 || $autoinsert )
		return $content;

	$parent = $post->ID;
	$children = wp_list_pages( 'echo=0&child_of=' . $parent . '&title_li=' );
	$depth = 4;
	$exclude = '';

	if ($children) {
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

add_filter('the_content', 'shailan_subpages_filter');
