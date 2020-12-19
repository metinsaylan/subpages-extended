<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

function shailan_subpages_dropdown_pages($args = '') {

	$defaults = array(
		'depth' => 0, 
		'child_of' => 0,
		'selected' => 0, 
		'echo' => 1,
		'name' => 'page_id', 
		'id' => '',
		'show_option_none' => '', 
		'show_option_no_change' => '',
		'option_none_value' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$pages = get_pages($r);
	$output = '';
	$name = esc_attr($name);
	// Back-compat with old system where both id and name were based on $name argument
	if ( empty($id) )
		$id = $name;

	if ( ! empty($pages) ) {
		$output = "<select name=\"$name\" id=\"$id\">\n";
		if ( $show_option_no_change )
			$output .= "\t<option value=\"-1\" " . selected( $selected, '-1', false ) . ">$show_option_no_change</option>";
		if ( $show_option_none )
			$output .= "\t<option value=\"" . esc_attr($option_none_value) . "\" " . selected( $selected, $option_none_value, false ) . ">$show_option_none</option>\n";
		$output .= "\t<option value=\"*full-branch*\" " . selected( $selected, "*full-branch*", false ) . ">*Full branch*</option>\n";
		$output .= walk_page_dropdown_tree($pages, $depth, $r);
		$output .= "</select>\n";
	}

	$output = apply_filters( 'wp_dropdown_pages', $output, $r, $pages );

	if ( $echo )
		echo $output;

	return $output;
}
