<?php
/*
Plugin Name: Subpages Extended
Plugin URI: https://metinsaylan.com/projects/wordpress/subpages-extended/
Description: List sub pages of any page, using shortcode and widget. Visit <a href="options-general.php?page=subpages-extended">settings page</a> for auto-insert option.
Version: 1.5.1
Author: Metin Saylan
Author URI: https://metinsaylan.com/
Text Domain: subpages-extended
*/

global $subpages_indexes;

// Utilities
include_once( 'class-shailan-walker-page.php' );

// Widget & shortcode
include_once( 'subpages-extended.php' ); // the widget
include_once( 'subpages-extended-shortcode.php' ); // the shortcode

// Filters
include_once( 'subpages-extended-filter-auto-insert.php' );
include_once( 'subpages-extended-filter-page-title.php' );

if( is_admin() ){
  include_once( 'wpa/wpa-plugins-core.php' );
  
  // Pages dropdown on widget options
  include_once( 'subpages-extended-util-dropdown-pages.php' );

  // Options & meta boxes
  include_once( 'subpages-extended-admin.php' );
  include_once( 'subpages-menu-label-metabox.php' );

  // Settings links
  add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpa_sp_add_settings_link' );
  function wpa_sp_add_settings_link( $links ) {
    $links[] = '<a href="options-general.php?page=subpages-extended">Settings</a>';
    $links[] = '<a href="https://metinsaylan.com/donate">Donate</a>';
    return $links;
  }
}