<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

function shailan_subpages_shortcode($atts) {

	global $post, $subpages_indexes;

	extract(shortcode_atts(array(
		'depth'        => 3,
		'show_date'    => false,
		'date_format'  => get_option('date_format'),
		'child_of'     => -1,
		'exclude'      => '',
		'include'      => '',
		'title_li'     => '',
		'echo'         => 1,
		'authors'      => '',
		'sort_column'  => 'menu_order, post_title',
		'sort_order'   => 'ASC',
		'link_before'  => '',
		'link_after'   => '',
		'exceptme' => false,
		'childof' => '',
		'title' => '',
		'rel' => ''
		), $atts));

		$walker = new Shailan_Walker_Page;
		$walker->set_rel( $rel );

	if('parent' == $childof || 'parent' == $child_of) {
		$parent = $post->post_parent; 
	} else {
		$parent = $childof;
		if(-1 != $child_of) { $parent = $child_of; }
		if($parent==''){ $parent = $post->ID; }
	}

	if($exceptme) { $exclude .= ','.$post->ID; }
	if($title == '*current*'){ $title = '<h3>' . get_the_title($parent) . '</h3>'; }

	$subpages_indexes += 1;
	$shortcode_id = $subpages_indexes;

	$children = wp_list_pages( 'echo=0&child_of=' . $parent . '&title_li=' );

	$subpage_args = array(
		'depth'        => $depth,
		'show_date'    => $show_date,
		'date_format'  => $date_format,
		'child_of'     => $parent,
		'exclude'      => $exclude,
		'include'      => $include,
		'title_li'     => '',
		'echo'         => false,
		'authors'      => $authors,
		'sort_column'  => $sort_column,
		'sort_order' => $sort_order,
		'link_before'  => $link_before,
		'link_after'   => $link_after,
		'walker' =>  $walker );

	if ($children) {
		$subpages = '<div id="shailan-subpages-' . $post->ID . '-' .$shortcode_id.'">'.$title.'<ul class="subpages">';
		$subpages .= wp_list_pages( $subpage_args );
		$subpages .= '</ul></div>';
	} else {
		$subpages = '"' . get_the_title($parent) . '" doesn\'t have any sub pages.';
	}

	return $subpages;
}

add_shortcode('subpages', 'shailan_subpages_shortcode');
