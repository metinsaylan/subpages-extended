<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

// Filters page title so that Subpages Extended menu title will be used
function shailan_page_title_filter( $title, $id  ){

	$subpages_menu_label = htmlspecialchars( stripcslashes ( get_post_meta ( $id, '_subpages_menu_label', true ) ) );
	$aiosp_menulabel = htmlspecialchars( stripcslashes ( get_post_meta ( $id, '_aioseop_menulabel', true ) ) );

	if('' != $subpages_menu_label){
		return $subpages_menu_label;
	} elseif( '' != $aiosp_menulabel ) {
		return $aiosp_menulabel;
	} else {
		return $title;
	}

} add_filter( 'walker_page_title', 'shailan_page_title_filter', 10, 2 );
